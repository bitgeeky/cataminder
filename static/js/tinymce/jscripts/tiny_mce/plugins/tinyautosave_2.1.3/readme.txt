TinyAutoSave plug-in for TinyMCE
Copyright (c) 2008-2010 Todd Northrop

ReadMe
Last updated May 9, 2010

Automatically saves the editor contents periodically and just before
leaving the current page.  Allows the user to rescue the contents of
the last auto-save, in case they did not intend to navigate away from
the current page or the browser window was closed before posting the
content.

Dual licensed under the MIT or GPL Version 2 licenses.
See mit-license.txt and gpl2-license.txt in the project root for details.

See changelog.txt for a summary of changes to the project.

TinyAutoSave project page:
http://tinyautosave.googlecode.com

See description of commands, options, properties, and methods below.

About the Author
	Speednet is the owner and developer of Lottery Post, the world's
	largest community of lottery players and home to the world's #1
	lottery results service. (www.lotterypost.com)

____________________________
TinyMCE Commands

	mceTinyAutoSaveRestore - Called when toolbar button is clicked.
		Restores the content currently in the auto-save storage.
			
	mceTinyAutoSave - Saves the current contents of the editor to the
		auto-save storage.  Within the plug-in, this command is
		executed in two ways: (1) with a timer that saves the contents
		periodically, and (2) when the current page is exited/closed.

____________________________
Configuration Options

	tinyautosave_key - (String, default = editor id) A string value used to
		identify the autosave storage and settings to use for the plugin
		instance. If tinyautosave_key is not specified, then the editor's id
		property is used. If you set the tinyautosave_key for all editors to
		the same value, that would create a single autosave storage instance
		and a single set of autosave settings to use with all editors.
		Because each key maintains its own plugin settings, tinyautosave_key
		can also be used to apply a different UI or behavior to individual
		editors. For example, two editors on the same page could use
		different progress images, or they could autosave at different
		intervals.

	tinyautosave_interval_seconds - (Number, default = 60) The number of
		seconds between automatic saves.  When the editor is first displayed,
		an autosave will not occur for at least this amount of time.

	tinyautosave_retention_minutes - (Number, default = 20) The number of
		minutes since the last autosave that content will remain in the
		rescue storage space before it is automatically expired.

	tinyautosave_minlength - (Number, default = 50) The minimum number of
		characters that must be in the editor before an autosave will occur.
		The character count includes all non-visible characters, such as HTML
		tags.  Although this can be set to 0 (zero), it is not recommended.
		Doing so would open the possibility that if the user accidentally
		refreshes the page, the empty editor contents would overwrite the
		rescue content, effectively defeating the purpose of the plugin.

	tinyautosave_oninit - (String, default = null) Name of a function that
		will be called immediately after the TinyAutoSave plugin is
		initialized. Can use dot-notation (i.e., "myObject.myFunction").
		This function is a good place to set public properties and anything
		else that alters the look and/or behavior of the TinyAutoSave plugin.
		The context of the function call (the value of 'this') is the
		TinyAutoSave plugin instance.
		
	tinyautosave_showsaveprogress - (Boolean, default = true) When true, the
		toolbar button will show a brief animation every time an autosave
		occurs.

____________________________
Public Properties

	editor - (Object) TinyMCE editor instance to which the plugin instance
		is attached.
		
	url - (String) The base URL of the plugin.
	
	key - (String) The value passed in the tinyautosave_key setting (or the
		editor id if nothing passed).
	
	onPreSave - (String, default = null) When set to the name of a function,
		that function will be called just before each auto-save occurs. The
		context of the function (the value of 'this') is set to the editor
		instance. The function must return a Boolean value indicating if the
		auto-save is to proceed normally (true) or be canceled (false).
	
	onPostSave - (String, default = null) When set to the name of a function,
		that function will be called just after each auto-save occurs. The
		context of the function (the value of 'this') is set to the editor
		instance. Any return value from the function is ignored.
	
	onSaveError - (String, default = null) When set to the name of a
		function, that function will be called every time an error occurs
		during an auto-save operation. The context of the function (the value
		of 'this') is set to the editor instance. Any return value from the
		function is ignored.
	
	onPreRestore - (String, default = null) When set to the name of a
		function, that function will be called just before each restore
		request is carried out. The context of the function (the value of
		'this') is set to the editor instance. The function must return a
		Boolean value indicating if the restore is to proceed normally (true)
		or be canceled (false).
	
	onPostRestore - (String, default = null) When set to the name of a
		function, that function will be called just after each restore
		request is carried out. The context of the function (the value of
		'this') is set to the editor instance. Any return value from the
		function is ignored.
	
	onRestoreError - (String, default = null) When set to the name of a
		function, that function will be called every time an error occurs
		during a restore operation. The context of the function (the value
		of 'this') is set to the editor instance. Any return value from the
		function is ignored.
	
	progressDisplayTime - (Number, default = 1200) Number of milliseconds
		the progress indicator/throbber will display after each auto-save.
		The default value of 1200 is the equivalent of 1.2 seconds.
	
	showSaveProgress - (Boolean, default = true) When true, the toolbar
		button will show a brief animation every time an autosave occurs.
		This property is initially set to the value of the 
		tinyautosave_showsaveprogress configuration option, but this
		property allows the animation to be controlled dynamically.

____________________________
Public Methods

	init() - Called by TinyMCE to initialize the plugin
	
	getInfo() - Called by TinyMCE to retrieve information about the plugin.
	
	clear() - Clears any auto-saved content currently stored, and "dims"
		the Restore toolbar button.
	
	hasSavedContent() - Returns true if there is auto-save content available
		to be restored, or false if not.
	
	setProgressImage() - Sets the URL of the image that will be displayed
		every time an auto-save occurs.
	
	
__
