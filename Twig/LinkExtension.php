<?php
/**
 * LinkExtension.php file
 *
 * File that contains the help wiki link extension class
 *
 * Licence MIT
 * Copyright (c) 2014 Multnomah Education Service District <http://www.mesd.k12.or.us>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * @filesource /src/Mesd/HelpWikiBundle/Twig/LinkExtension.php
 * @package    Mesd\HelpWikiBundle\Twig
 * @copyright  2014 (c) Multnomah Education Service District <http://www.mesd.k12.or.us>
 * @license    <http://opensource.org/licenses/MIT> MIT
 * @author     Curtis G Hanson <chanson@mesd.k12.or.us>
 * @version    0.1.0
 */
namespace Mesd\HelpWikiBundle\Twig;

use Twig_Extension;
//use Twig_Environment;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
//use Symfony\Component\EventDispatcher\EventDispatcher;

//use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\Routing\Router;
//use Symfony\Component\Routing\Matcher\UrlMatcher;
//use Symfony\Component\Routing\RequestContext;
//
use Mesd\HelpWikiBundle\Model\PageManager;
use Mesd\HelpWikiBundle\Model\LinkManager;
use Mesd\HelpWikiBundle\Entity\Link;

/**
 * Linker Extension
 * 
 */
class LinkExtension extends Twig_Extension
{
    /**
     * The service container
     * 
     * @var Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    protected $container;

    /**
     * The user request
     * 
     * @var Symfony\Component\HttpFoundation\Request $request
     */
    protected $request;

    /**
     * The symfony router component
     * 
     * @var Symfony\Component\Routing\Router $router
     */
    protected $router;

    /**
     * The Twig environment
     * 
     * @var \Twig_Environment $twig
     */
    protected $twig;

    /**
     * The block template path
     *
     * The Twig template to be used.
     * 
     * @var string
     */
    protected $template;

    /**
     * The block
     *
     * The block referenced in the template
     * 
     * @var string $block
     */
    protected $block;

    /**
     * The root breadcrumb path
     *
     * Although, not required, this extension allows you to define your
     * root (or base) routes in the extension configuration. You may also
     * define it in the routing.yml file, or when calling the twig function.
     * 
     * @var array(string) $root
     */
    protected $data = array();

    private $linkManager;
    private $pageManager;

    public function __construct(ContainerInterface $container, Router $router, PageManager $pm, LinkManager $lm)
    {
        $this->container   = $container;
        $this->router      = $router;
        $this->pageManager = $pm;
        $this->linkManager = $lm;

        //$config         = $this->container->getParameter('mesd_help_wiki');
        //$this->template = $config['template'];
        $this->template    = 'MesdHelpWikiBundle:Twig:link.html.twig';
    }

    /**
     * On Kernel Request
     *
     * Listen to kernel for main request:
     *  - ensures request not injected into Twig causing scope widening
     *  - retrieving request from container might yeild to internel (sub) request
     *  - retrieving request from container in constructor breaks CLI env (cache warning)
     *
     * @param GetResponseEvent $event The kernel event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if ($event->getRequestType() === HttpKernel::MASTER_REQUEST) {
            $this->request = $event->getRequest();
        }
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('mesd_help_wiki_linker',
                array($this, 'linkerFunction'),
                array(
                    'is_safe' => array('html'),
                    'needs_environment' => true
            )),
            new \Twig_SimpleFunction('mesd_help_wiki_page',
                array($this, 'pageFunction'),
                array(
                    'is_safe' => array('html'),
                    'needs_environment' => true
            )),
        );
    }

    public function linkerFunction(\Twig_Environment $twig, $options = array())
    {
        //extract($options);

        // get the alias
        $routeAlias  = $this->request->get('_route');
        $this->block = 'mesd_help_wiki_linker';

        $link = $this->linkManager->isLinked($routeAlias);
        
        // I can render the template block
        $template = $twig->loadTemplate($this->template);
        return $template->renderBlock($this->block, array('link' => $link, 'route' => $routeAlias));
    }

    public function pageFunction(\Twig_Environment $twig, $pageId, $options = array())
    {
        //extract($options);
        $this->block = 'mesd_help_wiki_page';

        $page = $this->pageManager->findById($pageId);
        
        $template = $twig->loadTemplate($this->template);
        return $template->renderBlock($this->block, array('page' => $page));
    }

    public function getName()
    {
        return 'mesd_help_wiki_link_extension';
    }

}