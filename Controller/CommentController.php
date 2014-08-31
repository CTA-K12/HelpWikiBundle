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
     * @return unknown
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MesdHelpWikiBundle:Comment')->findAll();

        return $this->render('MesdHelpWikiBundle:Comment:index.html.twig', array(
                'entities' => $entities,
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
        $comment->setPage($page);

        $form = $this->createCreateForm($comment, $pageId);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();

            return $this->redirect($this->generateUrl('page_show', array('slug' => $page->getSlug())));
        }

        return $this->render('MesdHelpWikiBundle:Comment:new.html.twig', array(
                'comment' => $comment,
                'form'    => $form->createView(),
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
                'action' => $this->generateUrl('comment_create', array('pageId' => $pageId)),
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
        $comment->setPage($page);

        $form   = $this->createCreateForm($comment, $pageId);

        return $this->render('MesdHelpWikiBundle:Comment:new.html.twig', array(
                'comment' => $comment,
                'form'    => $form->createView(),
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

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MesdHelpWikiBundle:Comment:show.html.twig', array(
                'entity'      => $entity,
                'delete_form' => $deleteForm->createView(),
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

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MesdHelpWikiBundle:Comment:edit.html.twig', array(
                'entity'      => $entity,
                'edit_form'   => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
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
                'action' => $this->generateUrl('comment_update', array('id' => $entity->getId())),
                'method' => 'PUT',
            )
        );

        $form->add('submit', 'submit', array('label' => 'Update'        )
        );

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

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('comment_edit', array('id' => $id)));
        }

        return $this->render('MesdHelpWikiBundle:Comment:edit.html.twig', array(
                'entity'      => $entity,
                'edit_form'   => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
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

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('comment'        )
        );
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
        ->setAction($this->generateUrl('comment_delete', array('id' => $id)))
        ->setMethod('DELETE')
        ->add('submit', 'submit', array('label' => 'Delete'))
        ->getForm()
        ;
    }

    /**
     *
     *
     * @param unknown $pageId
     * @return unknown
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
     * Lists all Comment entities.
     *
     * @param unknown $pageId
     * @return unknown
     */
    public function indexByPageAction($pageId)
    {
        $em = $this->getDoctrine()->getManager();

        $page = $em->getRepository('MesdHelpWikiBundle:Page')->find($pageId);

        $comments = $em->getRepository('MesdHelpWikiBundle:Comment')->findByPage($page);

        $deleteForms = array();

        foreach ($comments as $comment) {
            $deleteForms[$comment->getId()] = $this->createDeleteForm($comment->getId())->createView();
        }

        return $this->render('MesdHelpWikiBundle:Comment:indexByPage.html.twig', array(
                'comments'    => $comments,
                'deleteForms' => $deleteForms,
            )
        );
    }
}
