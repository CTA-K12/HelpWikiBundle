<?php

namespace Mesd\HelpWikiBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Doctrine\ORM\EntityRepository;

use Mesd\HelpWikiBundle\Entity\Page;
use Mesd\HelpWikiBundle\Form\DataTransformer\HeadingToPermalinkTransformer;

class PageType extends AbstractType
{

    private $page;

<<<<<<< HEAD
    public function __construct(Page $page) {
        $this->page = $page;
=======
    private $formType;

    public function __construct($formType)
    {
        $this->formType = $formType;
>>>>>>> a245e9aa27f96e4a47604f5a25bfbdfbef798f09
    }

    /**
     * Build Form
     *
     * Build a page form type form.
     *
     * @param FormBuilderInterface $builder
     * @param array   $options
     */
<<<<<<< HEAD
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $transformer = new HeadingToPermalinkTransformer();

        $builder
            ->add('title')
            ->add('slug')
            ->add(
                $builder
                    ->create('body', 'mesd_form_type_ckeditor', array())
                    ->addModelTransformer($transformer)
                )
        ;
=======
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new HeadingToPermalinkTransformer();

        $builder
        ->add('title')
        ->add('slug')
        ->add(
            $builder
                ->create( 'body', $this->formType, array())
                ->addModelTransformer($transformer)
        );
>>>>>>> a245e9aa27f96e4a47604f5a25bfbdfbef798f09

        $this->page = $options['data'];
        $pageId = $this->page->getId();

        if (!$pageId) {
<<<<<<< HEAD
            $builder
                ->add('parent')
                ->add(
                    $builder
                        ->create('body', 'mesd_form_type_ckeditor', array())
                        ->addModelTransformer($transformer)
                )
            ;
        } else {
            $builder->add(
                $builder
                    ->create('body', 'mesd_form_type_ckeditor', array())
=======
            $builder->add('parent')
            ->add(
                $builder
                    ->create('body', $this->formType, array())
                    ->addModelTransformer($transformer)
            );
        } else {
            $builder->add(
                $builder
                    ->create('body', $this->formType, array())
>>>>>>> a245e9aa27f96e4a47604f5a25bfbdfbef798f09
                    ->addModelTransformer($transformer, $this->page->getSlug())
            );

            $builder->addEventListener(
                FormEvents::PRE_SET_DATA,
                function(FormEvent $event) use ($pageId) {
                    $form = $event->getForm();

                    $formOptions = array(
                        'class'         => 'Mesd\HelpWikiBundle\Entity\Page',
                        'property'      => 'title',
                        'required'      => false,
                        'query_builder' => function(EntityRepository $er) use ($pageId) {
                            return $er->getPagesNotEqualToPage($pageId);
                            // build a custom query
                            // return $er->createQueryBuilder('u')->addOrderBy('fullName', 'DESC');

                            // or call a method on your repository that returns the query builder
                            // the $er is an instance of your UserRepository
                            // return $er->createOrderByFullNameQueryBuilder();
                        },
                   );

                    // create the field, this is similar the $builder->add()
                    // field name, field type, data, options
                    $form->add('parent', 'entity', $formOptions);
                }
           );
        }
    }

    /**
     * Set Default Options
     *
     * Set the entity linked to the data class
     *
     * @param OptionsResolverInterface $resolver
     */
<<<<<<< HEAD
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
=======
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
>>>>>>> a245e9aa27f96e4a47604f5a25bfbdfbef798f09
        $resolver->setDefaults(array(
            'data_class' => 'Mesd\HelpWikiBundle\Entity\Page'
        ));
    }

    /**
     * Get Name
     *
     * Get the name of the page form type service
     *
     * @return string
     */
    public function getName()
    {
        return 'mesd_help_wiki_page';
    }
}
