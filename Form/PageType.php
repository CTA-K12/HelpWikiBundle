<?php
/**
 * PageType.php file
 *
 * File that contains the help wiki page type form class
 *
 * Licence MIT
 * Copyright (c) 2014 Multnomah Education Service District <http://www.mesd.k12.or.us>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * @filesource /src/Mesd/HelpWikiBundle/Form/PageType.php
 * @package    Mesd\HelpWikiBundle\Form
 * @copyright  2014 (c) Multnomah Education Service District <http://www.mesd.k12.or.us>
 * @license    <http://opensource.org/licenses/MIT> MIT
 * @author     Curtis G Hanson <chanson@mesd.k12.or.us>
 * @version    {@inheritdoc}
 */
namespace Mesd\HelpWikiBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Doctrine\ORM\EntityRepository;

use Mesd\HelpWikiBundle\Entity\Page;
use Mesd\HelpWikiBundle\Form\DataTransformer\HeadingToPermalinkTransformer;

/**
 * Page Type
 *
 * The help wiki page form type
 *
 * @package    Mesd\HelpWikiBundle\Form
 * @copyright  2015 (c) Multnomah Education Service District <http://www.mesd.k12.or.us>
 * @license    <http://opensource.org/licenses/MIT> MIT
 * @author     Curtis G Hanson <chanson@mesd.k12.or.us>
 * @since      0.1.0
 */
class PageType extends AbstractType
{

    private $page;

    private $formType;

    public function __construct($formType)
    {
        $this->formType = $formType;
    }

    /**
     * Build Form
     *
     * Build a page form type form.
     *
     * @param FormBuilderInterface $builder
     * @param array   $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new HeadingToPermalinkTransformer();

        $builder
            ->add('title')
            ->add('slug')
            ->add($builder
                ->create('body', $this->formType, array())
                ->addModelTransformer($transformer)
            )
            ->add('status', 'mesd_help_wiki_page_status_type', array(
                'data' => 'PUBLISHED'
            ))
            ->add('standAlone')
            ->add('tags', 'mesd_help_wiki_tag_type', array(
                'required' => false
            ))
            ->add('links')
        ;

        $this->page = $options['data'];
        $pageId = $this->page->getId();

        if (!$pageId) {
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
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
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
