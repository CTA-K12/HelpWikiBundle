<?php
/**
 * TagType.php file
 *
 * File that contains the tag type class
 *
 * Licence MIT
 * Copyright (c) 2014 Multnomah Education Service District <http://www.mesd.k12.or.us>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * @filesource /src/Mesd/HelpWikiBundle/Form/Type/TagType.php
 * @package    Mesd\HelpWikiBundle\Form\Type
 * @copyright  2014 (c) Multnomah Education Service District <http://www.mesd.k12.or.us>
 * @license    <http://opensource.org/licenses/MIT> MIT
 * @author     Curtis G Hanson <chanson@mesd.k12.or.us>
 * @version    0.1.0
 */
namespace Mesd\HelpWikiBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Bridge\Doctrine\RegistryInterface;
use Mesd\HelpWikiBundle\Form\DataTransformer\EntitiesToStringTransformer;

/**
 * Form type for tags
 *
 * @author Curtis G Hanson    <chanson@mesd.k12.or.us>
 * @author Stepan Tanasiychuk <ceo@stfalcon.com>
 */
class TagType extends AbstractType
{

    protected $registry;

    /**
     * Constructor injection
     *
     * @param RegistryInterface $registry Doctrine registry object
     *
     * @return void
     */
    public function __construct(RegistryInterface $registry)
    {
        $this->registry = $registry;
    }

    /**
     * Builds the form.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new EntitiesToStringTransformer($this->registry->getManager());

        $builder->addModelTransformer($transformer);
    }

    /**
     * Returns the name of the parent type.
     *
     * @return string
     */
    public function getParent()
    {
        return 'text';
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'mesd_help_wiki_tag_type';
    }
}