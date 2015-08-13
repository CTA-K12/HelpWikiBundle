<?php
/**
 * CommentController.php file
 *
 * File that contains the form type comment controller class
 *
 * Licence MIT
 * Copyright (c) 2014 Multnomah Education Service District <http://www.mesd.k12.or.us>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * @filesource /src/Mesd/HelpWikiBundle/Controller/CommentController.php
 * @package    Mesd\HelpWikiBundle\Controller
 * @copyright  2014 (c) Multnomah Education Service District <http://www.mesd.k12.or.us>
 * @license    <http://opensource.org/licenses/MIT> MIT
 * @author     Curtis G Hanson <chanson@mesd.k12.or.us>
 * @version    {@inheritdoc}
 */
namespace Mesd\HelpWikiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Mesd\HelpWikiBundle\Entity\Comment;
use Mesd\HelpWikiBundle\Form\CommentType;

use Mesd\HelpWikiBundle\Model\Menu;

/**
 * Comment Controller
 *
 * This controller links all actions to the comment model
 *
 * @package    Mesd\HelpWikiBundle\Controller
 * @copyright  2014 (c) Multnomah Education Service District <http://www.mesd.k12.or.us>
 * @license    <http://opensource.org/licenses/MIT> MIT
 * @author     Curtis G Hanson <chanson@mesd.k12.or.us>
 * @since      0.1.0
 */
class CommentController extends Controller
{

    /**
     * List comments action
     *
     * Display a screen to view a listing of comments
     *
     * @return $this
     */
    public function listAction()
    {
        $mgr = $this->container->getParameter('mesd_help_wiki.doctrine_orm_entity_manager');
        $em  = $this->getDoctrine()->getManager($mgr);

        $comments = $em->getRepository('MesdHelpWikiBundle:Comment')->findAll();

        foreach ($comments as $en)
        {
            $flagForms[$en->getId()]    = $this->createFlagForm($en->getId())->createView();
            $deleteForms[$en->getId()]  = $this->createDeleteForm($en->getId())->createView();
            $approveForms[$en->getId()] = $this->createApproveForm($en->getId())->createView();
        }

        if (!$comments)
        {
            return $this->render('MesdHelpWikiBundle:Comment:list.html.twig', array(
                'comments'      => $comments,
                'menu'          => new Menu(),
            ));
        }

        return $this->render('MesdHelpWikiBundle:Comment:list.html.twig', array(
            'comments'      => $comments,
            'flag_forms'    => $flagForms,
            'delete_forms'  => $deleteForms,
            'approve_forms' => $approveForms,
            'menu'          => new Menu(),
        ));
    }

    /**
     * Create comment action
     *
     * Add a new comment
     *
     * @param  object  $request
     * @param  unknown $pageId
     * @return unknown
     */
    public function createAction(Request $request, $pageId)
    {
        $page = $this->getPage($pageId);

        $comment = new Comment();

        if (false === $this->get('security.context')->isGranted('CREATE', $comment))
        {
            throw new AccessDeniedException('Unauthorized access!');
        }

        $comment->setPage($page);

        $form = $this->createCreateForm($comment, $pageId);
        $form->handleRequest($request);

        if ($form->isValid())
        {
            $mgr = $this->container->getParameter('mesd_help_wiki.doctrine_orm_entity_manager');
            $em  = $this->getDoctrine()->getManager($mgr);
            $em->persist($comment);
            $em->flush();

            return $this->redirect($this->generateUrl('MesdHelpWikiBundle_page_view', array('slug' => $page->getSlug())));
        }

        return $this->render('MesdHelpWikiBundle:Comment:new.html.twig', array(
                'comment' => $comment,
                'form'    => $form->createView(),
                'menu'    => new Menu(),
        ));
    }

    /**
     * Create create form
     *
     * Create a form to add a comment
     *
     * @param  object  $comment
     * @param  unknown $pageId
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Comment $comment, $pageId)
    {
        $page = $this->getPage($pageId);

        $comment->setPage($page);

        $form = $this->createForm(new CommentType(), $comment, array(
                'action' => $this->generateUrl('MesdHelpWikiBundle_comment_create', array('pageId' => $pageId)),
                'method' => 'POST',
            )
        );

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * New comment action
     *
     * Display a screen to add a comment
     *
     * @param object  $request
     * @param unknown $pageId
     * @return unknown
     */
    public function newAction(Request $request, $pageId)
    {
        $page = $this->getPage($pageId);

        $comment = new Comment();

        if (false === $this->get('security.context')->isGranted('CREATE', $comment)) {
            throw new AccessDeniedException('Unauthorized access!');
        }

        $comment->setPage($page);

        $form   = $this->createCreateForm($comment, $pageId);

        return $this->render('MesdHelpWikiBundle:Comment:new.html.twig', array(
                'comment' => $comment,
                'form'    => $form->createView(),
                'menu'    => new Menu(),
            )
        );
    }

    /**
     * View comment action
     *
     * Display a screen to view a comment
     *
     * @param unknown $id
     * @return unknown
     */
    public function viewAction($id)
    {
        $mgr = $this->container->getParameter('mesd_help_wiki.doctrine_orm_entity_manager');
        $em  = $this->getDoctrine()->getManager($mgr);

        $en = $em->getRepository('MesdHelpWikiBundle:Comment')->find($id);

        if (!$en)
        {
            throw $this->createNotFoundException('Unable to find Comment entity.');
        }

        if (false === $this->get('security.context')->isGranted('VIEW', $en))
        {
            throw new AccessDeniedException('Unauthorized access!');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MesdHelpWikiBundle:Comment:view.html.twig', array(
                'en'      => $en,
                'delete_form' => $deleteForm->createView(),
                'menu'        => new Menu(),
            )
        );
    }

    /**
     * Edit comment action
     *
     * Display a screen to edit a comment
     *
     * @param unknown $id
     * @return unknown
     */
    public function editAction($id)
    {
        $mgr = $this->container->getParameter('mesd_help_wiki.doctrine_orm_entity_manager');
        $em  = $this->getDoctrine()->getManager($mgr);

        $en = $em->getRepository('MesdHelpWikiBundle:Comment')->find($id);

        if (!$en) {
            throw $this->createNotFoundException('Unable to find Comment entity.');
        }

        if (false === $this->get('security.context')->isGranted('EDIT', $en)) {
            throw new AccessDeniedException('Unauthorized access!');
        }

        $editForm   = $this->createEditForm($en);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MesdHelpWikiBundle:Comment:edit.html.twig', array(
                'en'      => $en,
                'edit_form'   => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
                'menu'        => new Menu(),
            )
        );
    }

    /**
     * Create edit form
     *
     * Create a form to edit a comment
     *
     * @param object  $entity The entity
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Comment $en)
    {
        $form = $this->createForm(new CommentType(), $en, array(
            'action' => $this->generateUrl('MesdHelpWikiBundle_comment_update', array('id' => $en->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Update comment action
     *
     * Update an edited comment
     *
     * @param object  $request
     * @param unknown $id
     * @return unknown
     */
    public function updateAction(Request $request, $id)
    {
        $mgr = $this->container->getParameter('mesd_help_wiki.doctrine_orm_entity_manager');
        $em  = $this->getDoctrine()->getManager($mgr);

        $en = $em->getRepository('MesdHelpWikiBundle:Comment')->find($id);

        if (!$en) {
            throw $this->createNotFoundException('Unable to find Comment entity.');
        }

        if (false === $this->get('security.context')->isGranted('EDIT', $en)) {
            throw new AccessDeniedException('Unauthorized access!');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm   = $this->createEditForm($en);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('MesdHelpWikiBundle_comment_edit', array('id' => $id)));
        }

        return $this->render('MesdHelpWikiBundle:Comment:edit.html.twig', array(
                'en'      => $en,
                'edit_form'   => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
                'menu'        => new Menu(),
            )
        );
    }

    /**
     * Deletes a Comment entity.
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
            $mgr = $this->container->getParameter('mesd_help_wiki.doctrine_orm_entity_manager');
            $em  = $this->getDoctrine()->getManager($mgr);
            $en  = $em->getRepository('MesdHelpWikiBundle:Comment')->find($id);

            if (!$en)
            {
                throw $this->createNotFoundException('Unable to find Comment entity.');
            }

            if (false === $this->get('security.context')->isGranted('DELETE', $en))
            {
                throw new AccessDeniedException('Unauthorized access!');
            }

            $em->remove($en);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('MesdHelpWikiBundle_comment_list'));
    }

    /**
     * Creates a form to delete a Comment entity by id.
     *
     *
     * @param mixed   $id The entity id
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
        ->setAction($this->generateUrl('MesdHelpWikiBundle_comment_delete', array('id' => $id)))
        ->setMethod('DELETE')
        ->add('delete', 'submit')
        ->getForm()
        ;
    }

    /**
     * Flags a comment
     *
     * @param  \Symfony\Component\HttpFoundation\Request  $request The request
     * @param  integer                                    $id      The comment id
     * @return \Symfony\Component\HttpFoundation\Response $this    The response
     */
    public function flagAction(Request $request, $id)
    {
        $form = $this->createFlagForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $mgr = $this->container->getParameter('mesd_help_wiki.doctrine_orm_entity_manager');
            $em  = $this->getDoctrine()->getManager($mgr);
            $en  = $em->getRepository('MesdHelpWikiBundle:Comment')->find($id);

            if (!$en)
            {
                throw $this->createNotFoundException('Unable to find Comment entity.');
            }

            if (false === $this->get('security.context')->isGranted('FLAG', $en))
            {
                throw new AccessDeniedException('Unauthorized access!');
            }
            
            $en->setStatus(Comment::FLAGGED);

            $em->flush();

            //return $this->redirect($this->generateUrl('MesdHelpWikiBundle_comment_edit', array('id' => $id)));
            return $this->redirect($this->generateUrl('MesdHelpWikiBundle_comment_list'));
        }

        return $this->redirect($this->generateUrl('MesdHelpWikiBundle_comment_list'));
    }

    /**
     * Creates a form to flag a Comment entity by id.
     *
     *
     * @param  mixed                        $id   The entity id
     * @return \Symfony\Component\Form\Form $this The form
     */
    private function createFlagForm($id)
    {
        return $this
            ->createFormBuilder()
            ->setAction($this->generateUrl('MesdHelpWikiBundle_comment_flag', array('id' => $id)))
            ->setMethod('PUT')
            ->add('flag', 'submit')
            ->getForm()
        ;
    }

    /**
     * Approves a Comment.
     *
     * @param  \Symfony\Component\HttpFoundation\Request  $request The request
     * @param  integer                                    $id      The comment id
     * @return \Symfony\Component\HttpFoundation\Response $this    The response
     */
    public function approveAction(Request $request, $id)
    {
        $form = $this->createApproveForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $en = $em->getRepository('MesdHelpWikiBundle:Comment')->find($id);

            if (!$en) {
                throw $this->createNotFoundException('Unable to find Comment entity.');
            }

            if (false === $this->get('security.context')->isGranted('APPROVE', $en))
            {
                throw new AccessDeniedException('Unauthorized access!');
            }

            $en->setStatus(Comment::APPROVED);

            $em->flush();

            //return $this->redirect($this->generateUrl('MesdHelpWikiBundle_comment_edit', array('id' => $id)));
            return $this->redirect($this->generateUrl('MesdHelpWikiBundle_comment_list'));
        }

        return $this->redirect($this->generateUrl('MesdHelpWikiBundle_comment_list'));
    }

    /**
     * Creates a form to approve a Comment by Id.
     *
     * @param  mixed                        $id   The entity id
     * @return \Symfony\Component\Form\Form $this The form
     */
    private function createApproveForm($id)
    {
        return $this
            ->createFormBuilder()
            ->setAction($this->generateUrl('MesdHelpWikiBundle_comment_approve', array('id' => $id)))
            ->setMethod('PUT')
            ->add('approve', 'submit')
            ->getForm()
        ;
    }

    /**
     *
     *
     * @param  integer $pageId A page id
     * @return Page    $page   A page entity 
     */
    protected function getPage($pageId)
    {
        $em = $this->getDoctrine()->getManager();

        $page = $em->getRepository('MesdHelpWikiBundle:Page')->find($pageId);

        if (!$page)
        {
            throw $this->createNotFoundException('Unable to find Page.');
        }

        return $page;
    }

    /**
     * List Comments by Page
     *
     * @param  integer                                    $pageId A page id 
     * @return \Symfony\Component\HttpFoundation\Response $this   A response
     */
    public function listByPageAction($pageId)
    {
        $comment  = new Comment();

        if (false === $this->get('security.context')->isGranted('VIEW', $comment))
        {
            throw new AccessDeniedException('Unauthorized access!');
        }

        $em       = $this->getDoctrine()->getManager();
        $page     = $em->getRepository('MesdHelpWikiBundle:Page')->find($pageId);
        $comments = $em->getRepository('MesdHelpWikiBundle:Comment')->findByPage($page);

        $flagForms    = array();
        $deleteForms  = array();
        $approveForms = array();

        foreach ($comments as $comment)
        {
            $flagForms[$comment->getId()]    = $this->createFlagForm($comment->getId())->createView();
            $deleteForms[$comment->getId()]  = $this->createDeleteForm($comment->getId())->createView();
            $approveForms[$comment->getId()] = $this->createApproveForm($comment->getId())->createView();
        }

        return $this->render('MesdHelpWikiBundle:Comment:listByPage.html.twig', array(
                'comments'      => $comments,
                'flag_forms'    => $flagForms,
                'delete_forms'  => $deleteForms,
                'approve_forms' => $approveForms,
                'menu'          => new Menu(),
            )
        );
    }
}
