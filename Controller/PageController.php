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
 * @version    0.1.0
 */
namespace Mesd\HelpWikiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Mesd\HelpWikiBundle\Entity\Page;
use Mesd\HelpWikiBundle\Form\PageType;

use Mesd\HelpWikiBundle\Entity\Comment;
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
    * Lists all Page entities.
    *
    * @return unknown
    */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MesdHelpWikiBundle:Page')->findByParent(null);

        usort($entities, array($this, 'cmp_obj'));
        //$child = $entities[1]->getChildren()->toArray();

        return $this->render('MesdHelpWikiBundle:Page:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
    * Creates a new Page entity.
    *
    * @param object $request
    * @return unknown
    */
    public function createAction(Request $request)
    {
        $entity = new Page();

        $form = $this->createCreateForm($entity);

        $form->add('routeAlias', 'hidden', array(
            'mapped' => false,
        ));

        $form->handleRequest($request);

        if ($form->isValid()) {
            // @todo this should be moved to the form type
            $routeAlias = $form->get('routeAlias')->getViewData();
            $this->container->set('routeAlias', $routeAlias);

            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('page_show', array('slug' => $entity->getSlug())));
        }

        return $this->render('MesdHelpWikiBundle:Page:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
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
     * @param     Page $entity                 The page object
     * @return    \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Page $entity)
    {
        $form = $this->createForm('mesd_help_wiki_page', $entity, array(
            'action' => $this->generateUrl('page_create'),
            'method' => 'POST',
        ));

        $form->add('save', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
    * Displays a form to create a new Page entity.
    *
    * @return unknown
    */
    public function newAction()
    {
        $entity = new Page();
        $form   = $this->createCreateForm($entity);

        $data = $this->getRequest()->query->get('routeAlias');

        if ($data) {
            $form->add('routeAlias', 'hidden', array(
                'mapped' => false,
                'data' => $data
            ));
        }

        return $this->render('MesdHelpWikiBundle:Page:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Finds and displays a Page entity.
    *
    * @param string $slug
    * @return unknown
    */
    public function showAction($slug)
    {
        $em = $this->getDoctrine()->getManager();

        $entity   = $em->getRepository('MesdHelpWikiBundle:Page')->findOneBySlug($slug);
        $comments = $em->getRepository('MesdHelpWikiBundle:Comment')->findByPage($entity->getId());
        $next     = $em->getRepository('MesdHelpWikiBundle:Page')->getNextPage($entity);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Page entity.');
        }

        $deleteForm = $this->createDeleteForm($entity->getId());

        $title = preg_replace('/\s*?\bpages?\b\s*?$/i', '', $entity->getTitle());


        return $this->render('MesdHelpWikiBundle:Page:show.html.twig', array(
            'subtitle'    => $title,
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
            'comments'    => $comments,
            'next'        => $next,
        ));
    }

    /**
    * Displays a form to edit an existing Page entity.
    *
    * @param integer $id
    * @return unknown
    */
    public function editAction($id)
    {
        $em     = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('MesdHelpWikiBundle:Page')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Page entity.');
        }
        else if (false === $this->isGrantedAction($entity, 'VIEW_ONLY')) {
            throw new AccessDeniedException();
        }

        $editForm   = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        $title = 'Edit ' . preg_replace('/\s*?\bpages?\b\s*?$/i', '', $entity->getTitle()) . ' Page';

        return $this->render('MesdHelpWikiBundle:Page:edit.html.twig', array(
            'subtitle'    => $title,
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Page entity.
    *
    *
    * @param object  $entity The entity
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Page $entity)
    {

        $form = $this->createForm(new PageType($entity), $entity, array(
            'action' => $this->generateUrl('page_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('save', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
    * Edits an existing Page entity.
    *
    * @param object  $request
    * @param unknown $id
    * @return unknown
    */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MesdHelpWikiBundle:Page')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Page entity.');
        }
        else if (false === $this->isGrantedAction($entity, 'VIEW_ONLY')) {
            throw new AccessDeniedException();
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm   = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('page_show', array('slug' => $entity->getSlug())));
        }

        return $this->render('MesdHelpWikiBundle:Page:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Deletes a Page entity.
    *
    * @param object  $request
    * @param unknown $id
    * @return unknown
    */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em     = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MesdHelpWikiBundle:Page')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Page entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('page'));
    }

    /**
    * Creates a form to delete a Page entity by id.
    *
    *
    * @param mixed   $id The entity id
    * @return \Symfony\Component\Form\Form The form
    */
    private function createDeleteForm($id)
    {
        return $this
            ->createFormBuilder()
            ->setAction($this->generateUrl('page_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }

    /**
    * Displays a form to reorder all Page entities.
    *
    * @return unknown
    */
    public function editOrderAction()
    {
        $em       = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MesdHelpWikiBundle:Page')->findByParent(null);

        usort($entities, array($this, 'cmp_obj'));

        return $this->render('MesdHelpWikiBundle:Page:editOrder.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
    * Is Granted
    *
    * @param unknown $entity
    * @param unknown $permissionType
    * @return unknown
    */
    private function isGrantedAction($entity, $permissionType)
    {
        // get all permissions for page
        // for each one, make sure no one's attempting to
        // access the edit screen who doesn't have permission
        $em          = $this->getDoctrine()->getManager();
        $permissions = $em->getRepository('MesdHelpWikiBundle:Permission')->findByPage($entity->getId());

        foreach ($permissions as $permission) {
            if (true === $this->get('security.context')->isGranted($permission->getRole()->getRole())) {
                if ($permissionType == $permission->getPermissionType()) {
                    return false;
                }
            }
        }
    }

    /* This is the static comparing function: */

    /**
    * Compare Object
    *
    * @param  Page $a
    * @param  Page $b
    * @return integer
    * @todo   There has to be a better way to handle this
    */
    static function cmp_obj(Page $a, Page $b)
    {
        $al = strtolower($a->getPrintOrder());
        $bl = strtolower($b->getPrintOrder());

        if ($al == $bl) {
            $ax = strtolower($a->getTitle());
            $bx = strtolower($b->getTitle());
            if ($ax == $bx) {
                return 0;
            }
            return ($ax > $bx) ? +1 : -1;
        }
        return ($al > $bl) ? +1 : -1;
    }

}
