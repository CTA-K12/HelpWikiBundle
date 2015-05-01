CKEDITOR.plugins.add('twig', {
    icons: 'twig',
    init:  function(editor) {
        
        // Add Editor Command
        editor.addCommand('twig', new CKEDITOR.dialogCommand('twigDialog'));

        // Add Toolbar Button > Opens Twig Dialog
        editor.ui.addButton('Twig', {
            label:   'Insert Twig',
            command: 'twig',
            toolbar: 'insert'
        });

        CKEDITOR.dialog.add('twigDialog', this.path + 'dialogs/twig.js');
    }
});