<?php
// src/Mesd/HelpWikiBundle/Form/Type/CKEditorType.php
namespace Mesd\HelpWikiBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class CKEditorType extends AbstractType
{
    // public function getDefaultOptions()
    public function getDefaultOptions(array $options)

    {
        // this does nothing
        // i added the class to the field type
        $resolver->setDefaults(array(
          'attr' => array('class' => 'ckeditor')
        ));
    }

    // public function getParent(array $options)
    public function getParent()
    {
        return 'textarea';
    }

    public function getName()
    {
        return 'ckeditor';
    }

}