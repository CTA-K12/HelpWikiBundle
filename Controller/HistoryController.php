<?php
/**
 * HistoryController.php file
 *
 * File that contains the history controller
 * The history contains all edits made to a page
 *
 * Licence MIT
 * Copyright (c) 2014 Multnomah Education Service District <http://www.mesd.k12.or.us>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * @filesource /src/Mesd/HelpWikiBundle/Controller/HistoryController.php
 * @package    Mesd\HelpWikiBundle\Controller
 * @copyright  2014 (c) Multnomah Education Service District <http://www.mesd.k12.or.us>
 * @license    <http://opensource.org/licenses/MIT> MIT
 * @author     Curtis G Hanson <chanson@mesd.k12.or.us>
 * @version    0.1.0
 * @deprecated This file is not used and will be removed in future versions
 */
namespace Mesd\HelpWikiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Mesd\HelpWikiBundle\Entity\History;
use Mesd\HelpWikiBundle\Form\HistoryType;

/**
 * History Controller
 *
 * This controller contains the actions for the history entity
 *
 * @package    Mesd\HelpWikiBundle\Controller
 * @copyright  2014 (c) Multnomah Education Service District <http://www.mesd.k12.or.us>
 * @license    <http://opensource.org/licenses/MIT> MIT
 * @author     Curtis G Hanson <chanson@mesd.k12.or.us>
 * @since      0.1.0
 */
class HistoryController extends Controller
{

    /**
     * History Index
     *
     * List all historical edits
     *
     * @since  0.1.0
     * @return \Twig $this
     */
    public function indexAction()
    {
        $em       = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('MesdHelpWikiBundle:History')->findAll();

        return $this->render('MesdHelpWikiBundle:History:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Page history index
     *
     * List all historical edits for a page
     *
     * @since  0.1.0
     * @param  integer $id   The page id
     * @return \Twig   $this
     */
    public function pageAction($id)
    {
        $em       = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('MesdHelpWikiBundle:History')->findByPage($id);

        return $this->render('MesdHelpWikiBundle:History:index.html.twig', array(
                'entities' => $entities,
        ));
    }

    /**
     * User history index
     *
     * List all historical edits for a user
     *
     * @since  0.1.0
     * @param  integer $id   The user id
     * @return \Twig   $this
     */
    public function userAction($id)
    {
        $em       = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('MesdHelpWikiBundle:History')->findByUser($id);

        return $this->render('MesdHelpWikiBundle:History:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Create historial record
     *
     * Creates a historical record for a page
     *
     * @since  0.1.0
     * @param  Request $request The request object
     * @return \Twig   $this
     */
    public function createAction(Request $request)
    {
        $entity = new History();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('history_show', array('id' => $entity->getId())));
        }

        return $this->render('MesdHelpWikiBundle:History:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Create a create form
     *
     * Creates the create history form
     *
     * @since      0.1.0
     * @deprecated This function is not used and is to be removed in future versions
     * @param      History                      $entity The history object
     * @return     \Symfony\Component\Form\Form $form   A symfony form object
     */
    private function createCreateForm(History $entity)
    {
        $form = $this->createForm(new HistoryType(), $entity, array(
            'action' => $this->generateUrl('history_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * New History Item
     *
     * Displays a page to create a history item
     *
     * @since      0.1.0
     * @deprecated This function is not used and is to be removed in future versions
     * @return     \Twig $this A twig html output
     */
    public function newAction()
    {
        $entity = new History();
        $form   = $this->createCreateForm($entity);

        return $this->render('MesdHelpWikiBundle:History:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Show History Item
     *
     * Displays a page to view a history item
     *
     * @since      0.1.0
     * @param      mixed $id   The entity id
     * @return     \Twig $this A twig html output
     */
    public function showAction($id)
    {
        $em     = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('MesdHelpWikiBundle:History')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find History entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MesdHelpWikiBundle:History:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edit History Item
     *
     * Displays a page to edit a history item
     *
     * @since      0.1.0
     * @deprecated This function is not used and is to be removed in future versions
     * @param      mixed $id   The entity id
     * @return     \Twig $this A twig html output
     */
    public function editAction($id)
    {
        $em     = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('MesdHelpWikiBundle:History')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find History entity.');
        }

        $editForm   = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MesdHelpWikiBundle:History:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Create an edit form
     *
     * Creates the edit history form
     *
     * @since      0.1.0
     * @deprecated This function is not used and is to be removed in future versions
     * @param      History                      $entity The history object
     * @return     \Symfony\Component\Form\Form $form   A symfony form object
     */
    private function createEditForm(History $entity)
    {
        $form = $this->createForm( new HistoryType(), $entity, array(
            'action' => $this->generateUrl('history_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Update History Item
     *
     * Updates a history item
     *
     * @since      0.1.0
     * @deprecated This function is not used and is to be removed in future versions
     * @param      Request                                         $request The request object
     * @param      mixed                                           $id      The entity id
     * @return     Symfony\Component\HttpFoundation\Response|\Twig $this    A redirect or twig html output
     */
    public function updateAction(Request $request, $id)
    {
        $em     = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('MesdHelpWikiBundle:History')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find History entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm   = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('history_edit', array('id' => $id)));
        }

        return $this->render('MesdHelpWikiBundle:History:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Delete History Item
     *
     * Creates the create history form
     *
     * @since      0.1.0
     * @deprecated This function is not used and is to be removed in future versions
     * @param      Request                                   $request The request object
     * @param      mixed                                     $id      The entity id
     * @return     Symfony\Component\HttpFoundation\Response $this    A controller redirect
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em     = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MesdHelpWikiBundle:History')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find History entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('history'));
    }

    /**
     * Create a delete form
     *
     * Creates the delete history form
     *
     * @since      0.1.0
     * @deprecated This function is not used and is to be removed in future versions
     * @param      mixed                        $id   The history id
     * @return     \Symfony\Component\Form\Form $form A symfony form object
     */
    private function createDeleteForm($id)
    {
        return $this
            ->createFormBuilder()
            ->setAction($this->generateUrl('history_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
