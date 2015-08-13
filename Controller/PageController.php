<?php
/**
 * PageController.php file
 *
 * File that contains the page controller class
 *
 * Licence MIT
 * Copyright (c) 2014 Multnomah Education Service District <http://www.mesd.k12.or.us>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * @filesource /src/Mesd/HelpWikiBundle/Controller/PageController.php
 * @package    Mesd\HelpWikiBundle\Controller
 * @copyright  2014 (c) Multnomah Education Service District <http://www.mesd.k12.or.us>
 * @license    <http://opensource.org/licenses/MIT> MIT
 * @author     Curtis G Hanson <chanson@mesd.k12.or.us>
 * @version    {@inheritdoc}
 */
namespace Mesd\HelpWikiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Doctrine\Common\Collections\ArrayCollection;

use Mesd\HelpWikiBundle\Form\DataTransformer\HeadingToPermalinkTransformer;

use Mesd\HelpWikiBundle\Entity\Page;
use Mesd\HelpWikiBundle\Entity\Comment;
use Mesd\HelpWikiBundle\Model\Menu;
use Mesd\HelpWikiBundle\Form\PageType;
use Mesd\HelpWikiBundle\Form\CommentType;

/**
 * Page Controller
 *
 * This controller links all actions to the page model
 *
 * @package    Mesd\HelpWikiBundle\Controller
 * @copyright  2014 (c) Multnomah Education Service District <http://www.mesd.k12.or.us>
 * @license    <http://opensource.org/licenses/MIT> MIT
 * @author     Curtis G Hanson <chanson@mesd.k12.or.us>
 * @since      0.1.0
 */
class PageController extends Controller
{

    /**
     * Table of Contents Action
     * 
     * Lists all Page entities sorted by chapter and print order.
     * Does not show pages where stand-alone property is true.
     *
     * @author    Curtis G Hanson <chanson@mesd.k12.or.us>
     * @copyright 2014 MESD
     * @since     0.1
     * @return    \Symfony\Component\HttpFoundation\Response $this
     */
    public function tocAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MesdHelpWikiBundle:Page')->getAllPagesByPrintOrder();

        $first = $entities->first();

        $entity = new Page();

        return $this->render('MesdHelpWikiBundle:Page:toc.html.twig', array(
            'first'    => $first,
            'entity'   => $entity,
            'entities' => $entities,
            'menu'     => new Menu(),
        ));
        
    }

    /**
     * Creates a new Page entity.
     *
     * @param  \Symfony\Component\HttpFoundation\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response $this
     */
    public function createAction(Request $request)
    {
        $entity = new Page();

        if (false === $this->get('security.context')->isGranted(['MANAGE', 'CREATE'], $entity)) {
            throw new AccessDeniedException('Unauthorized access!');
        }

        $form = $this->createCreateForm($entity);

        $form->add('routeAlias', 'hidden', array(
            'mapped' => false,
        ));

        $form->handleRequest($request);

        if ($form->isValid()) {
            if (true === $form->get('standAlone') && ($entity->getParent() || !$entity->getChildren()->isEmpty())) {
                throw new AccessDeniedException('A stand-alone page cannot have any parent or child pages!');
            }
            // @todo this should be moved to the form type
            $routeAlias = $form->get('routeAlias')->getViewData();
            $this->container->set('routeAlias', $routeAlias);

            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('MesdHelpWikiBundle_page_view', array(
                'slug' => $entity->getSlug(),
                'menu' => new Menu(),
            )));
        }

        return $this->render('MesdHelpWikiBundle:Page:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'menu'   => new Menu(),
        ));
    }

    /**
     * Create Create Form
     *
     * Creates a form to create a page
     *
     * @author    Curtis G Hanson <chanson@mesd.k12.or.us>
     * @copyright 2014 MESD
     * @since     0.1
     * @param     \Mesd\HelpWikiBundle\Entity\Page $entity A page object
     * @return    \Symfony\Component\Form\Form     $form   The create form
     */
    private function createCreateForm(Page $entity)
    {
        $form = $this->createForm('mesd_help_wiki_page', $entity, array(
            'action' => $this->generateUrl('MesdHelpWikiBundle_page_create'),
            'method' => 'POST',
        ));

        $form->add('save', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Page entity.
     *
     * @return \Symfony\Component\HttpFoundation\Response $this
     */
    public function newAction()
    {
        $entity = new Page();
        
        if (false === $this->get('security.context')->isGranted('CREATE', $entity)) {
            throw new AccessDeniedException('Unauthorized access!');
        }

        $form   = $this->createCreateForm($entity);

        $data = $this->getRequest()->query->get('routeAlias');

        // @todo move this to a form event
        if ($data) {
            $form->add('routeAlias', 'hidden', array(
                'mapped' => false,
                'data'   => $data
            ));
        }

        return $this->render('MesdHelpWikiBundle:Page:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'menu'   => new Menu(),
        ));
    }

    /**
     * Finds and displays a Page entity.
     *
     * @param string $slug
     * @return \Symfony\Component\HttpFoundation\Response $this
     */
    public function viewAction($slug)
    {
        $em = $this->getDoctrine()->getManager();

        $page     = $em->getRepository('MesdHelpWikiBundle:Page')->findOneBySlug($slug);
        $comment  = new Comment();
        $comments = $em->getRepository('MesdHelpWikiBundle:Comment')->findByPage($page->getId());
        $previous = $em->getRepository('MesdHelpWikiBundle:Page')->getPreviousPage($page);
        $next     = $em->getRepository('MesdHelpWikiBundle:Page')->getNextPage($page);

        if (!$page) {
            throw $this->createNotFoundException('Unable to find Page entity.');
        }

        if (false === $this->get('security.context')->isGranted('VIEW', $page)) {
            throw new AccessDeniedException('Unauthorized access!');
        }

        $deleteForm = $this->createDeleteForm($page->getId());

        $template = $this->render($page->getBody());

        $transformer = new HeadingToPermalinkTransformer();
        $body = $transformer->transform($template->getContent(), $page->getSlug());

        return $this->render('MesdHelpWikiBundle:Page:view.html.twig', array(
            'page'        => $page,
            'delete_form' => $deleteForm->createView(),
            'comment'     => $comment,
            'comments'    => $comments,
            'previous'    => $previous,
            'next'        => $next,
            'menu'        => new Menu(),
            'body'        => $body
        ));
    }

    /**
     * Finds and displays a stand-alone page entity.
     *
     * @param string $slug
     * @return \Symfony\Component\HttpFoundation\Response $this
     */
    public function viewStandAloneAction($slug)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MesdHelpWikiBundle:Page')->findOneBySlug($slug);

        $entities = array();
        $slugs    = array();

        if (!$entity) {
            if (true === extension_loaded('pspell')) {
                $pspell = \pspell_new('en');
                $terms  = preg_split('/[\-\_\.]/', strtolower($slug));

                $qb = $em->createQueryBuilder();
                $qb->select('p.slug')->from('MesdHelpWikiBundle:Page', 'p');

                foreach ($terms as $term) {
                    $suggestions = pspell_suggest($pspell, strtolower($term));

                    foreach ($suggestions as $suggestion) {
                        $suggest = preg_replace('/[^\w]/', '', strtolower($suggestion));

                        $cqb = [];


                        $cqb[] = $qb->expr()->like("LOWER(CONCAT(p.title, ''))", "'%$suggest%'");
                        $cqb[] = $qb->expr()->like("LOWER(CONCAT(p.slug, ''))", "'%$suggest%'");

                        $qb->orWhere(call_user_func_array(array($qb->expr(), 'orx'), $cqb));
                    }
                    
                    $entities = array_merge($entities, $qb->getQuery()->getResult());
                }
            }

            if (!empty($entities)) {
                foreach ($entities as $k => $v) {
                    $slugs[] = $v['slug'];
                }
                $slugs = array_unique($slugs);
            }

            return $this->render('MesdHelpWikiBundle:Page:noPage.html.twig', array(
                'slugs' => $slugs,
                'menu'  => new Menu(),
            ));
        }

        if (false === $this->get('security.context')->isGranted('VIEW', $entity)) {
            throw new AccessDeniedException('Unauthorized access!');
        }

        $title = preg_replace('/\s*?\bpages?\b\s*?$/i', '', $entity->getTitle());


        return $this->render('MesdHelpWikiBundle:Page:viewStandAlone.html.twig', array(
            'entity' => $entity,
            'menu'   => new Menu(),
        ));
    }

    /**
     * Displays a form to edit an existing Page entity.
     *
     * @param integer $id
     * @return \Symfony\Component\HttpFoundation\Response $this
     */
    public function editAction($id)
    {
        $em     = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('MesdHelpWikiBundle:Page')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Page entity.');
        }

        if (false === $this->get('security.context')->isGranted('EDIT', $entity)) {
            throw new AccessDeniedException('Unauthorized access!');
        }

        $editForm   = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        $title = 'Edit ' . preg_replace('/\s*?\bpages?\b\s*?$/i', '', $entity->getTitle()) . ' Page';

        return $this->render('MesdHelpWikiBundle:Page:edit.html.twig', array(
            'subtitle'    => $title,
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'menu'        => new Menu(),
        ));
    }

    /**
     * Creates a form to edit a Page entity.
     *
     *
     * @param object  $entity The entity
     * @return \Symfony\Component\Form\Form $form The form
     */
    private function createEditForm(Page $entity)
    {

        $form = $this->createForm('mesd_help_wiki_page', $entity, array(
            'action' => $this->generateUrl('MesdHelpWikiBundle_page_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('save', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Page entity.
     *
     * @param object  $request
     * @param integer $id
     * @return \Symfony\Component\HttpFoundation\Response $this
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MesdHelpWikiBundle:Page')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Page entity.');
        }

        if (false === $this->get('security.context')->isGranted('EDIT', $entity)) {
            throw new AccessDeniedException('Unauthorized access!');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm   = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            // @todo move to a validation group
            if (true === $editForm->getData()->getStandAlone() && ($entity->getParent() || !$entity->getChildren()->isEmpty())) {
                throw new AccessDeniedException('A stand-alone page cannot have any parent or child pages!');
            }

            $em->flush();

            return $this->redirect($this->generateUrl('MesdHelpWikiBundle_page_view', array('slug' => $entity->getSlug())));
        }

        return $this->render('MesdHelpWikiBundle:Page:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'menu'        => new Menu(),
        ));
    }

    /**
     * Deletes a Page entity.
     *
     * @param  Request $request
     * @param  integer $id
     * @return \Symfony\Component\HttpFoundation\AccessDeniedException $this|
     *         \Symfony\Component\HttpFoundation\RedirectResponse      $this
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em     = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MesdHelpWikiBundle:Page')->find($id);

            if (false === $this->get('security.context')->isGranted('DELETE', $entity)) {
                throw new AccessDeniedException('Unauthorized access!');
            }

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Page entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('MesdHelpWikiBundle_toc'));
    }

    /**
     * Creates a form to delete a Page entity by id.
     *
     *
     * @param  mixed   $id The entity id
     * @return \Symfony\Component\Form\Form $form The form
     */
    private function createDeleteForm($id)
    {
        return $this
            ->createFormBuilder()
            ->setAction($this->generateUrl('MesdHelpWikiBundle_page_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }

    /**
     * Displays a form to reorder all Page entities.
     *
     * @return \Symfony\Component\HttpFoundation\Response $this
     */
    public function editOrderAction()
    {
        $entity = new Page();

        if (false === $this->get('security.context')->isGranted('EDIT', $entity)) {
            throw new AccessDeniedException('Unauthorized access!');
        }
        
        $em       = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('MesdHelpWikiBundle:Page')->getAllPagesByPrintOrder();

        return $this->render('MesdHelpWikiBundle:Page:editOrder.html.twig', array(
            'entities' => $entities,
            'menu'     => new Menu(),
        ));
    }

    public function convertStringToSlugAction()
    {
        // convert to slug from any string
        // -------------------------------
        // strip special chars
        // strip html, js, etc
        // convert accented chars
        // replace camel case to dashes ('-')
        // replace delimiters to dashes ('-')
        // lowercase all words
        // trim to 256
        // validate slug
    }

    public function isSlugValidAction()
    {
        // validate the slug
        // -----------------
        // slug is:
        //     no special chars
        //     no uppercase
        //     no accented chars
        //     under 256
        //     check if unique
        //         if not
        //             add dash ('-') and incremental number to end of string
        //             ensure we strip the string to prevent going over 256
        //         if so
        //             done
        // return true or false
    }

}
