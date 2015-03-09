<?php
/**
 * RouteAliasType.php file
 *
 * File that contains the help wiki permission form type class
 *
 * Licence MIT
 * Copyright (c) 2014 Multnomah Education Service District <http://www.mesd.k12.or.us>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * @filesource /src/Mesd/HelpWikiBundle/Form/Type/RouteAliasType.php
 * @package    Mesd\HelpWikiBundle\Form\Type
 * @copyright  2014 (c) Multnomah Education Service District <http://www.mesd.k12.or.us>
 * @license    <http://opensource.org/licenses/MIT> MIT
 * @author     Curtis G Hanson <chanson@mesd.k12.or.us>
 * @version    0.1.0
 */
namespace Mesd\HelpWikiBundle\Form\Type;

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\Route;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RouteAliasType extends AbstractType
{
    private $router;

    private $routeCollection;

    private $routes = array();

    public function __construct(Router $router)
    {
        $this->router = $router;
        $this->routeCollection = $this->router->getRouteCollection();
        
        foreach (array_keys($this->routeCollection->all()) as $route) {
            if( strpos($route, "_") !== 0 ){
                $this->routes[strtolower($route)] = $route;
            }
        }
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'choices' => $this->routes
        ));
    }

    public function getParent()
    {
        return 'choice';
    }

    public function getName()
    {
        return 'mesd_help_wiki_routealias_type';
    }
}