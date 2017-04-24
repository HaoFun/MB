/**
 * @license Copyright (c) 2003-2017, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
    config.language = 'zh';
    config.uiColor = '#ffffaa';
    config.watch=700;
    config.height=300;
    config.toolbarCanCollapse = true;
    config.toolbar =
    [
        { name: 'document', items: [ 'Source' ] },
        { name: 'clipboard', items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
        { name: 'editing', items: [ 'Scayt' ] },
        { name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
        { name: 'insert', items: [ 'Image', 'Table', 'HorizontalRule', 'SpecialChar' ] },
        { name: 'tools', items: [ 'Maximize' ] },
        { name: 'Justify', items:['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock']},
        '/',
        { name: 'basicstyles', items: [ 'Bold', 'Italic', 'Strike','Underline','Strike','Subscript','Superscript', '-', 'RemoveFormat' ] },
        { name: 'paragraph', items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote' ] },
        { name: 'styles', items: [ 'Font', 'FontSize' ,'TextColor','BGColor'] },
        { name: 'about', items: [ 'About' ] }
    ];

    config.filebrowserBrowseUrl = '/ckfinder/ckfinder.html';
    config.filebrowserImageBrowseUrl = '/ckfinder/ckfinder.html?Type=Images';
    config.filebrowserFlashBrowseUrl = '/ckfinder/ckfinder.html?Type=Flash';
    config.filebrowserUploadUrl = '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files'; //可上傳一般檔案
    config.filebrowserImageUploadUrl = '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images';//可上傳圖檔


};
