<?php

namespace Mesd\HelpWikiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Mesd\HelpWikiBundle\Entity\PagePermission;
use Mesd\HelpWikiBundle\Form\PagePermissionType;

use Mesd\HelpWikiBundle\Model\Menu;
/**
 * PagePermission controller.
 *
 */
class PagePermissionController extends Controller
{

    /**
     * Lists all PagePermission entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MesdHelpWikiBundle:PagePermission')->findAll();

        return $this->render('MesdHelpWikiBundle:PagePermission:index.html.twig', array(
            'entities' => $entities,
            'menu'     => new Menu(),
        ));
    }
    /**
     * Creates a new PagePermission entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new PagePermission();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('pagepermission_show', array('id' => $entity->getId())));
        }

        return $this->render('MesdHelpWikiBundle:PagePermission:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'menu'   => new Menu(),
        ));
    }

    /**
    * Creates a form to create a PagePermission entity.
    *
    * @param PagePermission $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(PagePermission $entity)
    {
        $form = $this->createForm(new PagePermissionType(), $entity, array(
            'action' => $this->generateUrl('pagepermission_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new PagePermission entity.
     *
     */
    public function newAction()
    {
        $entity = new PagePermission();
        $form   = $this->createCreateForm($entity);

        return $this->render('MesdHelpWikiBundle:PagePermission:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'menu'   => new Menu(),
        ));
    }

    /**
     * Finds and displays a PagePermission entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MesdHelpWikiBundle:PagePermission')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PagePermission entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MesdHelpWikiBundle:PagePermission:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
            'menu'        => new Menu(),
        ));
    }

    /**
     * Displays a form to edit an existing PagePermission entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MesdHelpWikiBundle:PagePermission')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PagePermission entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MesdHelpWikiBundle:PagePermission:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'menu'        => new Menu(),
        ));
    }

    /**
    * Creates a form to edit a PagePermission entity.
    *
    * @param PagePermission $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(PagePermission $entity)
    {
        $form = $this->createForm(new PagePermissionType(), $entity, array(
            'action' => $this->generateUrl('pagepermission_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing PagePermission entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MesdHelpWikiBundle:PagePermission')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PagePermission entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('pagepermission_edit', array('id' => $id)));
        }

        return $this->render('MesdHelpWikiBundle:PagePermission:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'menu'        => new Menu(),
        ));
    }
    /**
     * Deletes a PagePermission entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MesdHelpWikiBundle:PagePermission')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find PagePermission entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('pagepermission'));
    }

    /**
     * Creates a form to delete a PagePermission entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('pagepermission_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
