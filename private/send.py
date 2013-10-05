import datetime
import time
while True:
rows = db(db.task.status=='pending'&db.task.ntime==datetime.date.today()).select()
for row in rows:
	
if mail.send(to=row.mailing,subject=row.name,message=row.description):
	row.update_record(status='sent')
else:
	row.update_record(status='failed')
db.commit()
time.sleep(1) # check every second
