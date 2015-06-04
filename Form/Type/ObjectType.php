<?php
/**
 * ObjectType.php file
 *
 * File that contains the help wiki object form type class
 *
 * Licence MIT
 * Copyright (c) 2014 Multnomah Education Service District <http://www.mesd.k12.or.us>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * @filesource /src/Mesd/HelpWikiBundle/Form/Type/ObjectType.php
 * @package    Mesd\HelpWikiBundle\Form\Type
 * @copyright  2014 (c) Multnomah Education Service District <http://www.mesd.k12.or.us>
 * @license    <http://opensource.org/licenses/MIT> MIT
 * @author     Curtis G Hanson <chanson@mesd.k12.or.us>
 * @version    {@inheritdoc}
 */
namespace Mesd\HelpWikiBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ObjectType extends AbstractType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'choices' => array(
                'PAGE'           => 'Page',
                'TAG'            => 'Tag',
                'COMMENT'        => 'Comment',
                'LINK'           => 'Link',
                'HISTORY'        => 'History',
                'PAGEPERMISSION' => 'Page Permission',
                'PERMISSION'     => 'Permission',
                'MENU'           => 'Menu',
            )
        ));
    }

    public function getParent()
    {
        return 'choice';
    }

    public function getName()
    {
        return 'mesd_help_wiki_object_type';
    }
}