CKEDITOR.dialog.add('twigDialog', function(editor) {
    return {
        title:     'Twig Properties',
        minWidth:  400,
        minHeight: 200,
        contents:  [
            {
                id:       'tab-basic',
                label:    'Basic Settings',
                elements: [
                    {
                        type: 'text',
                        id:   'twig',
                        label: 'Twig',
                        validate: CKEDITOR.dialog.validate.notEmpty("Twig field cannot be empty.")
                    },
                    {
                        type: 'text',
                        id:   'title',
                        label: 'Explanation',
                        validate: CKEDITOR.dialog.validate.notEmpty("Explanation field cannot be empty.")
                    }
                ]
            },
            {
                id:       'tab-adv',
                label:    'Advanced Settings',
                elements: [
                    {
                        type:  'text',
                        id:    'id',
                        label: 'Id'
                    }
                ]
            }
        ],
        onOk: function() {
            var dialog = this;

            var twig = editor.document.createElement('twig');
            twig.setAttribute('title', dialog.getValueOf('tab-basic', 'title'));
            twig.setText(dialog.getValueOf('tab-basic', 'twig'));

            var id = dialog.getValueOf( 'tab-adv', 'id' );
            if (id) {
                twig.setAttribute('id', id);
            }

            editor.insertElement(twig);
        }
    };
});