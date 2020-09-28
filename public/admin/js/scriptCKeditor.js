
$(function () {
    // Replace the <textarea id="editor1"> with a CKEditor
    // instance, using default configuration.
    CKEDITOR.replace('article_contenue')
    //bootstrap WYSIHTML5 - text editor
    $('.textarea').wysihtml5()
})