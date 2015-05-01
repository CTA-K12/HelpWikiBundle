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
 * @version    0.1.0
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
     * Lists all Comment entities.
     *
     * @return $this
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MesdHelpWikiBundle:Comment')->findAll();

        foreach ($entities as $entity) {
            $flagForms[$entity->getId()]    = $this->createFlagForm($entity->getId())->createView();
            $deleteForms[$entity->getId()]  = $this->createDeleteForm($entity->getId())->createView();
            $approveForms[$entity->getId()] = $this->createApproveForm($entity->getId())->createView();
        }

        return $this->render('MesdHelpWikiBundle:Comment:index.html.twig', array(
                'entities'      => $entities,
                'flag_forms'    => $flagForms,
                'delete_forms'  => $deleteForms,
                'approve_forms' => $approveForms,
                'menu'          => new Menu(),
            )
        );
    }

    /**
     * Creates a new Comment entity.
     *
     * @param  object  $request
     * @param  unknown $pageId
     * @return unknown
     */
    public function createAction(Request $request, $pageId)
    {
        $page = $this->getPage($pageId);

        $comment = new Comment();

        if (false === $this->get('security.context')->isGranted('CREATE', $comment)) {
            throw new AccessDeniedException('Unauthorized access!');
        }

        $comment->setPage($page);

        $form = $this->createCreateForm($comment, $pageId);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();

            return $this->redirect($this->generateUrl('MesdHelpWikiBundle_page_show', array('slug' => $page->getSlug())));
        }

        return $this->render('MesdHelpWikiBundle:Comment:new.html.twig', array(
                'comment' => $comment,
                'form'    => $form->createView(),
                'menu'    => new Menu(),
            )
        );
    }

    /**
     * Creates a form to create a Comment entity.
     *
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
     * Displays a form to create a new Comment entity.
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
     * Finds and displays a Comment entity.
     *
     * @param unknown $id
     * @return unknown
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MesdHelpWikiBundle:Comment')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Comment entity.');
        }

        if (false === $this->get('security.context')->isGranted('VIEW', $entity)) {
            throw new AccessDeniedException('Unauthorized access!');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MesdHelpWikiBundle:Comment:show.html.twig', array(
                'entity'      => $entity,
                'delete_form' => $deleteForm->createView(),
                'menu'        => new Menu(),
            )
        );
    }

    /**
     * Displays a form to edit an existing Comment entity.
     *
     * @param unknown $id
     * @return unknown
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MesdHelpWikiBundle:Comment')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Comment entity.');
        }

        if (false === $this->get('security.context')->isGranted('EDIT', $entity)) {
            throw new AccessDeniedException('Unauthorized access!');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MesdHelpWikiBundle:Comment:edit.html.twig', array(
                'entity'      => $entity,
                'edit_form'   => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
                'menu'        => new Menu(),
            )
        );
    }

    /**
     * Creates a form to edit a Comment entity.
     *
     *
     * @param object  $entity The entity
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Comment $entity)
    {
        $form = $this->createForm(new CommentType(), $entity, array(
            'action' => $this->generateUrl('MesdHelpWikiBundle_comment_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Comment entity.
     *
     * @param object  $request
     * @param unknown $id
     * @return unknown
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MesdHelpWikiBundle:Comment')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Comment entity.');
        }

        if (false === $this->get('security.context')->isGranted('EDIT', $entity)) {
            throw new AccessDeniedException('Unauthorized access!');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('MesdHelpWikiBundle_comment_edit', array('id' => $id)));
        }

        return $this->render('MesdHelpWikiBundle:Comment:edit.html.twig', array(
                'entity'      => $entity,
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
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MesdHelpWikiBundle:Comment')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Comment entity.');
            }

            if (false === $this->get('security.context')->isGranted('DELETE', $entity)) {
                throw new AccessDeniedException('Unauthorized access!');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('MesdHelpWikiBundle_comment_index'));
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
     * Flags a Comment.
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
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MesdHelpWikiBundle:Comment')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Comment entity.');
            }

            if (false === $this->get('security.context')->isGranted('FLAG', $entity)) {
                throw new AccessDeniedException('Unauthorized access!');
            }
            $entity->setStatus(Comment::FLAGGED);

            $em->flush();

            //return $this->redirect($this->generateUrl('MesdHelpWikiBundle_comment_edit', array('id' => $id)));
            return $this->redirect($this->generateUrl('MesdHelpWikiBundle_comment_index'));
        }

        return $this->redirect($this->generateUrl('MesdHelpWikiBundle_comment_index'));
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
            $entity = $em->getRepository('MesdHelpWikiBundle:Comment')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Comment entity.');
            }

            if (false === $this->get('security.context')->isGranted('APPROVE', $entity)) {
                throw new AccessDeniedException('Unauthorized access!');
            }

            $entity->setStatus(Comment::APPROVED);

            $em->flush();

            //return $this->redirect($this->generateUrl('MesdHelpWikiBundle_comment_edit', array('id' => $id)));
            return $this->redirect($this->generateUrl('MesdHelpWikiBundle_comment_index'));
        }

        return $this->redirect($this->generateUrl('MesdHelpWikiBundle_comment_index'));
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

        if (!$page) {
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
    public function indexByPageAction($pageId)
    {
        $comment  = new Comment();

        if (false === $this->get('security.context')->isGranted('VIEW', $comment)) {
            throw new AccessDeniedException('Unauthorized access!');
        }

        $em       = $this->getDoctrine()->getManager();
        $page     = $em->getRepository('MesdHelpWikiBundle:Page')->find($pageId);
        $comments = $em->getRepository('MesdHelpWikiBundle:Comment')->findByPage($page);

        $flagForms    = array();
        $deleteForms  = array();
        $approveForms = array();

        foreach ($comments as $comment) {
            $flagForms[$comment->getId()]    = $this->createFlagForm($comment->getId())->createView();
            $deleteForms[$comment->getId()]  = $this->createDeleteForm($comment->getId())->createView();
            $approveForms[$comment->getId()] = $this->createApproveForm($comment->getId())->createView();
        }

        return $this->render('MesdHelpWikiBundle:Comment:indexByPage.html.twig', array(
                'comments'      => $comments,
                'flag_forms'    => $flagForms,
                'delete_forms'  => $deleteForms,
                'approve_forms' => $approveForms,
                'menu'          => new Menu(),
            )
        );
    }
}
