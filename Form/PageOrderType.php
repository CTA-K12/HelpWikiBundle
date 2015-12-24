<?php
/**
 * PageOrderType.php file
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
class PageOrderType extends AbstractType
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
        $builder
            ->add('parent')
            ->add('left')
            ->add('right')
            ->add('level')
            ->add('position')
        ;
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
        return 'mesd_help_wiki_page_order';
    }
}
