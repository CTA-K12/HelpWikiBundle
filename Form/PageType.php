<?php

namespace MESD\HelpWikiBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Doctrine\ORM\EntityRepository;

use MESD\HelpWikiBundle\Entity\Page;
use MESD\HelpWikiBundle\Form\DataTransformer\HeadingToPermalinkTransformer;

class PageType extends AbstractType
{

    private $page;

    public function __construct(Page $page)
    {
        $this->page = $page;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new HeadingToPermalinkTransformer();

        $builder
            ->add('title')
            ->add('slug')
            ->add(
                $builder->create('body', 'ckeditor', array())
                        ->addModelTransformer($transformer)
            );

        $pageId = $this->page->getId();

        if (!$pageId) {
            $builder->add('parent')
                    ->add(
                        $builder->create('body', 'ckeditor', array())
                                ->addModelTransformer($transformer)
                    );
        } else {
            $builder->add(
                        $builder->create('body', 'ckeditor', array())
                                ->addModelTransformer($transformer, $this->page->getSlug())
                    );
            $builder->addEventListener(
                FormEvents::PRE_SET_DATA,
                function(FormEvent $event) use ($pageId) {
                    $form = $event->getForm();

                    $formOptions = array(
                        'class'         => 'MESD\HelpWikiBundle\Entity\Page',
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
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MESD\HelpWikiBundle\Entity\Page'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'mesd_helpwikibundle_page';
    }
}