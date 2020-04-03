/**
 * Copyright (c) 2003-2012, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	config.filebrowserImageUploadUrl = '/ckupload.php?type=img';
    config.filebrowserFlashUploadUrl = '/ckupload.php?type=flash';
	config.filebrowserUploadUrl = '/ckupload.php?type=file';
    config.width = "620"; //文本域宽度
    config.height = "250";//文本域高
};

