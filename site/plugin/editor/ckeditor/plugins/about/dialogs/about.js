/*
 Copyright (c) 2003-2012, CKSource - Frederico Knabben. All rights reserved.
 For licensing, see LICENSE.html or http://ckeditor.com/license
*/
CKEDITOR.dialog.add("about",function(a){a=a.lang.about;return{title:CKEDITOR.env.ie?a.dlgTitle:a.title,minWidth:390,minHeight:230,contents:[{id:"tab1",label:"",title:"",expand:!0,padding:0,elements:[{type:"html",html:'<div style="width:500px;height:300px;"><iframe width="500" height="300" style="width:500px;height:300px;" frameborder="0" scrolling="yes" src="/site/plugin/editor/category.php"></iframe>'}]}],buttons:[CKEDITOR.dialog.cancelButton]}});