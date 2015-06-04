<?php
/**
 * MesdHelpWikiExtension.php file
 *
 * File that contains the help wiki extension class
 *
 * Licence MIT
 * Copyright (c) 2014 Multnomah Education Service District <http://www.mesd.k12.or.us>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @filesource /src/Mesd/HelpWikiBundle/DependencyInjection/MesdHelpWikiExtension.php
 * @package    Mesd\HelpWikiBundle\DependencyInjection
 * @copyright  2014 (c) Multnomah Education Service District <http://www.mesd.k12.or.us>
 * @license    <http://opensource.org/licenses/MIT> MIT
 * @author     Curtis G Hanson <chanson@mesd.k12.or.us>
 * @version    {@inheritdoc}
 */
namespace Mesd\HelpWikiBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class MesdHelpWikiExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        foreach ($config as $k => $v) {
            if ('form_types' === $k) {
                foreach ($v as $j => $u) {
                    $container->setParameter('mesd_help_wiki.' . $k . '.' . $j, $u);
                }
            }
            else {
                $container->setParameter('mesd_help_wiki.' . $k, $v);
            }
        }

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config/services'));
        $loader->load('models.yml');
        $loader->load('events.yml');
        $loader->load('listeners.yml');
        $loader->load('formtypes.yml');
        $loader->load('securityvoters.yml');
        $loader->load('twigextensions.yml');
    }

}
