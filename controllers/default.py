# -*- coding: utf-8 -*-
# this file is released under public domain and you can use without limitations

#########################################################################
## This is a samples controller
## - index is the default action of any application
## - user is required for authentication and authorization
## - download is for downloading files uploaded in the db (does streaming)
## - call exposes all registered services (none by default)
#########################################################################

import datetime
@auth.requires_login()
def index():
	redirect(URL('index2'))
	return dict()
def index2():
	response.flash = T("Welcome "+auth.user.first_name+" ")
	top3=db(auth.user.first_name==db.task.author).select(db.task.ALL,orderby=db.task.ndate)
	return dict(top3=top3)
#	return dict()
def showbooks():
    """
    example action using the internationalization operator T and flash
    rendered by views/default/index.html or views/generic.html

    if you need a simple wiki simple replace the two lines below with:
    return auth.wiki()
    """
    books=db(db.books.author==auth.user.first_name).select(db.books.name,db.books.created)
    #print books
    name=auth.user.first_name
    return dict(books=books,name=name)
def createbook():
	return dict()
def addbook():
	name=request.post_vars['name']
	author=auth.user.first_name
	db.books.insert(name=name,author=author,created=datetime.date.today())
	redirect(URL('showbooks'))
	return dict()
def showtasks():
    tasks=db(db.task.author==auth.user.first_name).select(db.task.name,db.task.tdate,db.task.ttime)
    name=auth.user.first_name
    return dict(tasks=tasks,name=name)
def open():
	option=request.post_vars['option']
	bookname=request.post_vars['check']
	if option=='OPEN':
		#notes=db((db.notes.author==auth.user.first_name)&(db.notes.bookname==bookname)).select(db.notes.name,db.notes.created,db.notes.heading,db.notes.contents)
		notes=db((db.notes.author==auth.user.first_name)&(db.notes.bookname==bookname)).select(db.notes.ALL)
		return dict(bookname=bookname,notes=notes)
	elif option=='DELETE':
		db(db.notes.bookname==bookname).delete()
		db(db.books.name==bookname).delete()
		redirect(URL('index2'))
def newnote():
	bookname=request.post_vars['bookname']
	return dict(bookname=bookname)
def note():
	name={}
	tags=request.post_vars['tags']
	name=request.post_vars['name']
	bookname=request.post_vars['bookname']
	db.tags.insert(name=tags,notename=name,author=auth.user.first_name)
	return dict(name=name,bookname=bookname)


def saveform():
	bookname=request.post_vars['bookname']
	name2=request.post_vars['name']
	content=request.post_vars['content']
	head=request.post_vars['head']
	#print content
	#print head
	name1=auth.user.first_name
	names=db(db.notes.id>0).select(db.notes.name)
	names_list=[]
	for i in range(len(names)):
		names_list.append(names[i]['name'])
	if name2 not in names_list:
		db.notes.insert(name=name2,heading=head,author=name1,contents=content,created=datetime.date.today(),modified=datetime.date.today(),bookname=bookname)
	else:
		db(db.notes.name==name2).update(name=name2,heading=head,author=name1,contents=content,created=datetime.date.today(),modified=datetime.date.today(),bookname=bookname)	
	return dict()
def display():
	option=request.post_vars['option']
	notename=request.post_vars['check']
	bname=request.post_vars['bname']
	print bname
	if option=='OPEN':
		note=db((db.notes.name==notename)&(db.notes.author==auth.user.first_name)).select(db.notes.ALL)
		return dict(note=note)
	elif option=='DELETE':
		dnode=db(db.notes.name==notename).select(db.notes.contents,db.notes.heading)
		db.trash.insert(name=notename,bookname=bname,author=auth.user.first_name,contents=dnode[0]['contents'],heading=dnode[0]['heading'])
		db(db.notes.name==notename).delete()
		redirect(URL('showbooks'))

def newtask():
	db.task.author.default=auth.user.first_name
	db.task.status.default='pending'
	db.task.mailing.default=auth.user.email
	form = SQLFORM(db.task)
	if form.process().accepted:
		response.flash = 'NEW TASK CREATED'
		redirect(URL('index2'))
	return dict(form=form)
def opentask():
	option=request.vars['option']
	taskname=request.vars['check']
	if option=='OPEN' or session.flag == "flag":
		if session.taskname == None:
			session.taskname = taskname
			session.flag = "flag"
		record=db(db.task.name==session.taskname).select().first()
		form=SQLFORM(db.task,record)
		if form.accepts(request.vars, session):
			response.flash = 'record inserted'
			redirect(URL('index2'))
		elif form.errors:
			response.flash='errors in form'
		return dict(form=form)
	elif option=='DELETE':
		db(db.task.name==taskname).delete()
		redirect(URL('index2'))
def search():
	key=request.post_vars['key']
	print "hello1"+key
	notenames=db((db.tags.name.like('%'+key+'%'))&(db.tags.author==auth.user.first_name)).select(db.tags.notename)
	print "hello"
	print notenames
	if key=='':
		print "got it"
		notenames={}
		print notenames
	return dict(notenames=notenames,author=auth.user.first_name)
def trash():
    trash=db(db.trash.author==auth.user.first_name).select(db.trash.name,db.trash.bookname,db.trash.heading,db.trash.contents)
    name=auth.user.first_name
    return dict(trash=trash,name=name)

def trash2():
	option=request.post_vars['option']
	notename=request.post_vars['check']
	bname=request.post_vars['bname']
	if option=='OPEN':
		tnote=db((db.trash.name==notename)&(db.trash.author==auth.user.first_name)).select(db.trash.ALL)
		return dict(tnote=tnote)
	elif option=='RESTORE':
		dnode=db(db.trash.name==notename).select(db.trash.contents,db.trash.heading)
		db.notes.insert(name=notename,bookname=bname,author=auth.user.first_name,contents=dnode[0]['contents'],heading=dnode[0]['heading'],created=datetime.date.today(),modified=datetime.date.today())
		db(db.trash.name==notename).delete()
		redirect(URL('index2'))

def do_download(filename,content):
	response.headers['Content-Disposition']='attachment;filename='+filename
	response.headers['Content-Type']='txt/csv'
	return content.getvalue()
'''FOR DOWNLOADING'''
def create():
	import cStringIO
	content=cStringIO.StringIO()
	content.write('Title : '+str(request.args(1))+"\r\n"+'Description : '+str(request.args(2))+"\r\n")
	filename=str('Notes_'+request.args(0))
	return do_download(filename,content)

def user():
    """
    exposes:
    http://..../[app]/default/user/login
    http://..../[app]/default/user/logout
    http://..../[app]/default/user/register
    http://..../[app]/default/user/profile
    http://..../[app]/default/user/retrieve_password
    http://..../[app]/default/user/change_password
    use @auth.requires_login()
        @auth.requires_membership('group name')
        @auth.requires_permission('read','table name',record_id)
    to decorate functions that need access control
    """
    return dict(form=auth())


def download():
    """
    allows downloading of uploaded files
    http://..../[app]/default/download/[filename]
    """
    return response.download(request, db)


def call():
    """
    exposes services. for example:
    http://..../[app]/default/call/jsonrpc
    decorate with @services.jsonrpc the functions to expose
    supports xml, json, xmlrpc, jsonrpc, amfrpc, rss, csv
    """
    return service()


@auth.requires_signature()
def data():
    """
    http://..../[app]/default/data/tables
    http://..../[app]/default/data/create/[table]
    http://..../[app]/default/data/read/[table]/[id]
    http://..../[app]/default/data/update/[table]/[id]
    http://..../[app]/default/data/delete/[table]/[id]
    http://..../[app]/default/data/select/[table]
    http://..../[app]/default/data/search/[table]
    but URLs must be signed, i.e. linked with
      A('table',_href=URL('data/tables',user_signature=True))
    or with the signed load operator
      LOAD('default','data.load',args='tables',ajax=True,user_signature=True)
    """
    return dict(form=crud())
