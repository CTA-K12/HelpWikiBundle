<?php
/**
 * TagType.php file
 *
 * File that contains the comment type form class
 *
 * Licence MIT
 * Copyright (c) 2014 Multnomah Education Service District <http://www.mesd.k12.or.us>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * @filesource /src/Mesd/HelpWikiBundle/Form/TagType.php
 * @package    Mesd\HelpWikiBundle\Form
 * @copyright  2014 (c) Multnomah Education Service District <http://www.mesd.k12.or.us>
 * @license    <http://opensource.org/licenses/MIT> MIT
 * @author     Curtis G Hanson <chanson@mesd.k12.or.us>
 * @version    0.1.0
 */
namespace Mesd\HelpWikiBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Tag Type
 *
 * The help wiki comment form type
 *
 * @package   Mesd\HelpWikiBundle\Form
 * @copyright 2015 (c) Multnomah Education Service District <http://www.mesd.k12.or.us>
 * @license   <http://opensource.org/licenses/MIT> MIT
 * @author    Curtis G Hanson <chanson@mesd.k12.or.us>
 * @since     0.2.0
 * @todo      Not even sure if this file is being used. I'm positive it isn't.
 */
class TagType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('pages')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Mesd\HelpWikiBundle\Entity\Tag'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'mesd_helpwikibundle_tag';
    }
}
