(function() {
    var strPluginURL;
    tinymce.create('tinymce.plugins.ccSimpleUploaderPlugin', {
        init: function(ed, url) 
        {
            strPluginURL = url;                         // store the URL for future use..
            ed.addCommand('mceccSimpleUploader', function() {
                ccSimpleUploader();              
            });
            ed.addButton('ccSimpleUploader', {
                title: 'ccSimpleUploader',
                cmd: 'mceccSimpleUploader',
                image: url + '/img/ccSimpleUploader.png'
            });
        },
        createControl: function(n, cm) {
            return null;
        },
        getPluginURL: function() {
            return strPluginURL;
        },
        getInfo: function() {
            return {
                longname: 'ccSimpleUploader plugin',                
                author: 'Timur Kovalev',
                authorurl: 'http://www.creativecodedesign.com',
                infourl: 'http://www.creativecodedesign.com',
                version: "0.1"
            };
        }
    });
    tinymce.PluginManager.add('ccSimpleUploader', tinymce.plugins.ccSimpleUploaderPlugin);
})();

function ccSimpleUploader(field_name, url, type, win) {    
    var strPluginPath = tinyMCE.activeEditor.plugins.ccSimpleUploader.getPluginURL();                             
    var strUploaderURL = strPluginPath + "/uploader.php";                                                         
    var strUploadPath = tinyMCE.activeEditor.getParam(
             'plugin_ccSimpleUploader_upload_path');                     
    var strSubstitutePath = tinyMCE.activeEditor.getParam(
             'plugin_ccSimpleUploader_upload_substitute_path');      

    if (strUploaderURL.indexOf("?") < 0)                                                                          
        strUploaderURL = strUploaderURL + "?type=" + type + "&d=" + 
             strUploadPath + "&subs=" + strSubstitutePath; 
    else
        strUploaderURL = strUploaderURL + "&type=" + type + "&d=" + 
             strUploadPath + "&subs=" + strSubstitutePath;
    
    tinyMCE.activeEditor.windowManager.open({                                                                     
        file            : strUploaderURL,
        title           : 'cc Simple Uploader',
        width           : 400,  
        height          : 100,
        resizable       : "yes", 
        inline          : 1,                                                                                      
        close_previous  : "no"
    }, {
        window : win,
        input : field_name
    });
  
    return false;
}

function ClosePluginPopup (strReturnURL) {
    var win = tinyMCEPopup.getWindowArg("window");                                               
    if (!win)
        tinyMCE.activeEditor.execCommand('mceInsertContent', false, strReturnURL);
    else
    {
        win.document.getElementById(
              tinyMCEPopup.getWindowArg("input")).value = strReturnURL;    
        if (typeof(win.ImageDialog) != "undefined")                                              
        {        
            if (win.ImageDialog.getImageData) win.ImageDialog.getImageData();                    
            if (win.ImageDialog.showPreviewImage) win.ImageDialog.showPreviewImage(
                  strReturnURL);
        }    
    }
    tinyMCEPopup.close();                                                                         
}

