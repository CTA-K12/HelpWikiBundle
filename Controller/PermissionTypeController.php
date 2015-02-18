<?php
/**
 * PermissionTypeController.php file
 *
 * File that contains the permission types controller class
 *
 * Licence MIT
 * Copyright (c) 2014 Multnomah Education Service District <http://www.mesd.k12.or.us>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * @filesource /src/Mesd/HelpWikiBundle/Controller/PermissionTypeController.php
 * @package    Mesd\HelpWikiBundle\Controller
 * @copyright  2014 (c) Multnomah Education Service District <http://www.mesd.k12.or.us>
 * @license    <http://opensource.org/licenses/MIT> MIT
 * @author     Curtis G Hanson <chanson@mesd.k12.or.us>
 * @version    0.1.0
 */
namespace Mesd\HelpWikiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Mesd\HelpWikiBundle\Entity\PermissionType;
use Mesd\HelpWikiBundle\Form\PermissionTypeType;

/**
 * PermissionType controller.
 *
 */
class PermissionTypeController extends Controller
{

    /**
     * Lists all PermissionType entities.
     *
     * @return unknown
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MesdHelpWikiBundle:PermissionType')->findAll();

        return $this->render('MesdHelpWikiBundle:PermissionType:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Creates a new PermissionType entity.
     *
     * @param object  $request
     * @return unknown
     */
    public function createAction(Request $request)
    {
        $entity = new PermissionType();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('permissiontype_show', array('id' => $entity->getId())));
        }

        return $this->render('MesdHelpWikiBundle:PermissionType:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a PermissionType entity.
     *
     *
     * @param object  $entity The entity
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(PermissionType $entity)
    {
        $form = $this->createForm(new PermissionTypeType(), $entity, array(
            'action' => $this->generateUrl('permissiontype_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new PermissionType entity.
     *
     * @return unknown
     */
    public function newAction()
    {
        $entity = new PermissionType();
        $form   = $this->createCreateForm($entity);

        return $this->render('MesdHelpWikiBundle:PermissionType:new.html.twig', array(
                'entity'   => $entity,
                'form'     => $form->createView(),
           )
       );
    }

    /**
     * Finds and displays a PermissionType entity.
     *
     * @param unknown $id
     * @return unknown
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MesdHelpWikiBundle:PermissionType')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PermissionType entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MesdHelpWikiBundle:PermissionType:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing PermissionType entity.
     *
     * @param unknown $id
     * @return unknown
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MesdHelpWikiBundle:PermissionType')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PermissionType entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MesdHelpWikiBundle:PermissionType:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a PermissionType entity.
     *
     *
     * @param object  $entity The entity
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(PermissionType $entity)
    {
        $form = $this->createForm(new PermissionTypeType(), $entity, array(
            'action' => $this->generateUrl('permissiontype_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing PermissionType entity.
     *
     * @param object  $request
     * @param unknown $id
     * @return unknown
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MesdHelpWikiBundle:PermissionType')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PermissionType entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('permissiontype_edit', array('id' => $id)));
        }

        return $this->render('MesdHelpWikiBundle:PermissionType:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a PermissionType entity.
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
            $entity = $em->getRepository('MesdHelpWikiBundle:PermissionType')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find PermissionType entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('permissiontype'));
    }

    /**
     * Creates a form to delete a PermissionType entity by id.
     *
     *
     * @param mixed   $id The entity id
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('permissiontype_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
