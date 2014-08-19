<?php
/**
 * /tmp/phptidy-sublime-buffer.php
 *
 * @author Morgan Estes <morgan.estes@gmail.com>
 * @package default
 */


namespace Mesd\HelpWikiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Mesd\HelpWikiBundle\Entity\Permission;
use Mesd\HelpWikiBundle\Form\PermissionType;

/**
 * Permission controller.
 *
 */
class PermissionController extends Controller
{

    /**
     * Lists all Permission entities.
     *
     * @return unknown
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository( 'MesdHelpWikiBundle:Permission' )->findAll();

        return $this->render( 'MesdHelpWikiBundle:Permission:index.html.twig', array(
                'entities' => $entities,
            )
        );
    }

    /**
     * Creates a new Permission entity.
     *
     * @param object  $request
     * @return unknown
     */
    public function createAction( Request $request )
    {
        $entity = new Permission();
        $form = $this->createCreateForm( $entity );
        $form->handleRequest( $request );

        if ( $form->isValid() ) {
            $em = $this->getDoctrine()->getManager();
            $em->persist( $entity );
            $em->flush();

            return $this->redirect( $this->generateUrl( 'permission_show', array('id' => $entity->getId())));
        }

        return $this->render( 'MesdHelpWikiBundle:Permission:new.html.twig', array(
                'entity' => $entity,
                'form'   => $form->createView(),
            )
        );
    }

    /**
     * Creates a form to create a Permission entity.
     *
     *
     * @param object  $entity The entity
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm( Permission $entity )
    {
        $form = $this->createForm( new PermissionType(), $entity, array(
                'action' => $this->generateUrl( 'permission_create' ),
                'method' => 'POST',
            )
        );

        $form->add( 'submit', 'submit', array( 'label' => 'Create')
        );

        return $form;
    }

    /**
     * Displays a form to create a new Permission entity.
     *
     * @return unknown
     */
    public function newAction()
    {
        $entity = new Permission();
        $form   = $this->createCreateForm( $entity );

        return $this->render( 'MesdHelpWikiBundle:Permission:new.html.twig', array(
                'entity' => $entity,
                'form'   => $form->createView(),
            )
        );
    }

    /**
     * Finds and displays a Permission entity.
     *
     * @param unknown $id
     * @return unknown
     */
    public function showAction( $id )
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository( 'MesdHelpWikiBundle:Permission' )->find( $id );

        if ( !$entity ) {
            throw $this->createNotFoundException( 'Unable to find Permission entity.' );
        }

        $deleteForm = $this->createDeleteForm( $id );

        return $this->render( 'MesdHelpWikiBundle:Permission:show.html.twig', array(
                'entity'      => $entity,
                'delete_form' => $deleteForm->createView(),
            )
        );
    }

    /**
     * Displays a form to edit an existing Permission entity.
     *
     * @param unknown $id
     * @return unknown
     */
    public function editAction( $id )
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository( 'MesdHelpWikiBundle:Permission' )->find( $id );

        if ( !$entity ) {
            throw $this->createNotFoundException( 'Unable to find Permission entity.' );
        }

        $editForm = $this->createEditForm( $entity );
        $deleteForm = $this->createDeleteForm( $id );

        return $this->render( 'MesdHelpWikiBundle:Permission:edit.html.twig', array(
                'entity'      => $entity,
                'edit_form'   => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            )
        );
    }

    /**
     * Creates a form to edit a Permission entity.
     *
     *
     * @param object  $entity The entity
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm( Permission $entity )
    {
        $form = $this->createForm( new PermissionType(), $entity, array(
                'action' => $this->generateUrl( 'permission_update', array( 'id' => $entity->getId() ) ),
                'method' => 'PUT',
            )
        );

        $form->add( 'submit', 'submit', array( 'label' => 'Update')
        );

        return $form;
    }

    /**
     * Edits an existing Permission entity.
     *
     * @param object  $request
     * @param unknown $id
     * @return unknown
     */
    public function updateAction( Request $request, $id )
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository( 'MesdHelpWikiBundle:Permission' )->find( $id );

        if ( !$entity ) {
            throw $this->createNotFoundException( 'Unable to find Permission entity.' );
        }

        $deleteForm = $this->createDeleteForm( $id );
        $editForm = $this->createEditForm( $entity );
        $editForm->handleRequest( $request );

        if ( $editForm->isValid() ) {
            $em->flush();

            return $this->redirect( $this->generateUrl( 'permission_edit', array( 'id' => $id ))
            );
        }

        return $this->render( 'MesdHelpWikiBundle:Permission:edit.html.twig', array(
                'entity'      => $entity,
                'edit_form'   => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            )
        );
    }

    /**
     * Deletes a Permission entity.
     *
     * @param object  $request
     * @param unknown $id
     * @return unknown
     */
    public function deleteAction( Request $request, $id )
    {
        $form = $this->createDeleteForm( $id );
        $form->handleRequest( $request );

        if ( $form->isValid() ) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository( 'MesdHelpWikiBundle:Permission' )->find( $id );

            if ( !$entity ) {
                throw $this->createNotFoundException( 'Unable to find Permission entity.' );
            }

            $em->remove( $entity );
            $em->flush();
        }

        return $this->redirect( $this->generateUrl( 'permission')
        );
    }

    /**
     * Creates a form to delete a Permission entity by id.
     *
     *
     * @param mixed   $id The entity id
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm( $id )
    {
        return $this->createFormBuilder()
        ->setAction( $this->generateUrl( 'permission_delete', array( 'id' => $id ) ) )
        ->setMethod( 'DELETE' )
        ->add( 'submit', 'submit', array( 'label' => 'Delete' ) )
        ->getForm()
        ;
    }
}
