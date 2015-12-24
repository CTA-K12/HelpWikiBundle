<?php
/**
 * PageOrderCollectionType.php file
 *
 * File that contains the help wiki page order type form class
 *
 * Licence MIT
 * Copyright (c) 2015 Multnomah Education Service District <http://www.mesd.k12.or.us>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * @filesource /src/Mesd/HelpWikiBundle/Form/PageOrderType.php
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
use Mesd\HelpWikiBundle\Form\PageOrderType;

/**
 * Page Order Type
 *
 * The help wiki page order form type
 *
 * @package    Mesd\HelpWikiBundle\Form
 * @copyright  2015 (c) Multnomah Education Service District <http://www.mesd.k12.or.us>
 * @license    <http://opensource.org/licenses/MIT> MIT
 * @author     Curtis G Hanson <chanson@mesd.k12.or.us>
 * @since      0.1.0
 */
class PageOrderCollectionType extends AbstractType
{
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
        $builder->add('pages', 'collection', array(
            'type'         => new PageOrderType(),
            'by_reference' => false,
        ));

        $builder->addEventListener(
            FormEvents::SUBMIT,
            function(FormEvent $event) {
                $data = $event->getData();

                //foreach ($data->getPages() as $page)
                //{
                //    var_dump(array(
                //        'page'     => $page->getSlug(),
                //        'id'       => $page->getId(),
                //        'position' => $page->getPosition(),
                //        'level'    => $page->getLevel(),
                //    ));
                //}
                //exit;
                $event->setData($data);


            }
        );
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
            'data_class' => 'Mesd\HelpWikiBundle\Model\PageOrder'
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
        return 'mesd_help_wiki_page_order_collection';
    }
}
