<?php
/**
 * PermissionType.php file
 *
 * File that contains the help wiki permission form type class
 *
 * Licence MIT
 * Copyright (c) 2014 Multnomah Education Service District <http://www.mesd.k12.or.us>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * @filesource /src/Mesd/HelpWikiBundle/Form/Type/PermissionType.php
 * @package    Mesd\HelpWikiBundle\Form\Type
 * @copyright  2014 (c) Multnomah Education Service District <http://www.mesd.k12.or.us>
 * @license    <http://opensource.org/licenses/MIT> MIT
 * @author     Curtis G Hanson <chanson@mesd.k12.or.us>
 * @version    {@inheritdoc}
 */
namespace Mesd\HelpWikiBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PermissionType extends AbstractType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'choices' => array(
                'CREATE'   => 'Create',
                'VIEW'     => 'View',
                'EDIT'     => 'Edit',
                'DELETE'   => 'Delete',
                'RESTORE'  => 'Restore',
                'FLAG'     => 'Flag',
                'APPROVE'  => 'Approve',
                'RESTRICT' => 'Restrict',
                'UPLOAD'   => 'Upload',
                'MANAGE'   => 'Manage',
            )
        ));
    }

    public function getParent()
    {
        return 'choice';
    }

    public function getName()
    {
        return 'mesd_help_wiki_permission_type';
    }
}