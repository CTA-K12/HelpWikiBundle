<?php

namespace Mesd\HelpWikiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Mesd\HelpWikiBundle\Entity\History;
use Mesd\HelpWikiBundle\Form\HistoryType;

/**
 * History controller.
 *
 */
class HistoryController extends Controller
{

    /**
     * Lists all History entities.
     *
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository( 'MesdHelpWikiBundle:History' )->findAll();

        return $this->render( 'MesdHelpWikiBundle:History:index.html.twig', array(
                'entities' => $entities,
            )
        );
    }


    /**
     * Lists all History entities for a page.
     *
     */
    public function pageAction( $id ) {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository( 'MesdHelpWikiBundle:History' )->findByPage( $id );

        return $this->render( 'MesdHelpWikiBundle:History:index.html.twig', array(
                'entities' => $entities,
            )
        );
    }


    /**
     * Lists all History entities for a user.
     *
     */
    public function userAction( $id ) {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository( 'MesdHelpWikiBundle:History' )->findByUser( $id );

        return $this->render( 'MesdHelpWikiBundle:History:index.html.twig', array(
                'entities' => $entities,
            )
        );
    }


    /**
     * Creates a new History entity.
     *
     */
    public function createAction( Request $request ) {
        $entity = new History();
        $form = $this->createCreateForm( $entity );
        $form->handleRequest( $request );

        if ( $form->isValid() ) {
            $em = $this->getDoctrine()->getManager();
            $em->persist( $entity );
            $em->flush();

            return $this->redirect( $this->generateUrl( 'history_show', array( 'id' => $entity->getId() )        )
            );
        }

        return $this->render( 'MesdHelpWikiBundle:History:new.html.twig', array(
                'entity' => $entity,
                'form'   => $form->createView(),
            )
        );
    }

    /**
     * Creates a form to create a History entity.
     *
     * @param History $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm( History $entity ) {
        $form = $this->createForm( new HistoryType(), $entity, array(
                'action' => $this->generateUrl( 'history_create' ),
                'method' => 'POST',
            )
        );

        $form->add( 'submit', 'submit', array( 'label' => 'Create'        )
        );

        return $form;
    }

    /**
     * Displays a form to create a new History entity.
     *
     */
    public function newAction() {
        $entity = new History();
        $form   = $this->createCreateForm( $entity );

        return $this->render( 'MesdHelpWikiBundle:History:new.html.twig', array(
                'entity' => $entity,
                'form'   => $form->createView(),
            )
        );
    }

    /**
     * Finds and displays a History entity.
     *
     */
    public function showAction( $id ) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository( 'MesdHelpWikiBundle:History' )->find( $id );

        if ( !$entity ) {
            throw $this->createNotFoundException( 'Unable to find History entity.' );
        }

        $deleteForm = $this->createDeleteForm( $id );

        return $this->render( 'MesdHelpWikiBundle:History:show.html.twig', array(
                'entity'      => $entity,
                'delete_form' => $deleteForm->createView(),
            )
        );
    }

    /**
     * Displays a form to edit an existing History entity.
     *
     */
    public function editAction( $id ) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository( 'MesdHelpWikiBundle:History' )->find( $id );

        if ( !$entity ) {
            throw $this->createNotFoundException( 'Unable to find History entity.' );
        }

        $editForm = $this->createEditForm( $entity );
        $deleteForm = $this->createDeleteForm( $id );

        return $this->render( 'MesdHelpWikiBundle:History:edit.html.twig', array(
                'entity'      => $entity,
                'edit_form'   => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            )
        );
    }

    /**
     * Creates a form to edit a History entity.
     *
     * @param History $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm( History $entity ) {
        $form = $this->createForm( new HistoryType(), $entity, array(
                'action' => $this->generateUrl( 'history_update', array( 'id' => $entity->getId() ) ),
                'method' => 'PUT',
            )
        );

        $form->add( 'submit', 'submit', array( 'label' => 'Update'        )
        );

        return $form;
    }
    /**
     * Edits an existing History entity.
     *
     */
    public function updateAction( Request $request, $id ) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository( 'MesdHelpWikiBundle:History' )->find( $id );

        if ( !$entity ) {
            throw $this->createNotFoundException( 'Unable to find History entity.' );
        }

        $deleteForm = $this->createDeleteForm( $id );
        $editForm = $this->createEditForm( $entity );
        $editForm->handleRequest( $request );

        if ( $editForm->isValid() ) {
            $em->flush();

            return $this->redirect( $this->generateUrl( 'history_edit', array( 'id' => $id )        )
            );
        }

        return $this->render( 'MesdHelpWikiBundle:History:edit.html.twig', array(
                'entity'      => $entity,
                'edit_form'   => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            )
        );
    }
    /**
     * Deletes a History entity.
     *
     */
    public function deleteAction( Request $request, $id ) {
        $form = $this->createDeleteForm( $id );
        $form->handleRequest( $request );

        if ( $form->isValid() ) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository( 'MesdHelpWikiBundle:History' )->find( $id );

            if ( !$entity ) {
                throw $this->createNotFoundException( 'Unable to find History entity.' );
            }

            $em->remove( $entity );
            $em->flush();
        }

        return $this->redirect( $this->generateUrl( 'history'        )
        );
    }

    /**
     * Creates a form to delete a History entity by id.
     *
     * @param mixed   $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm( $id ) {
        return $this->createFormBuilder()
        ->setAction( $this->generateUrl( 'history_delete', array( 'id' => $id ) ) )
        ->setMethod( 'DELETE' )
        ->add( 'submit', 'submit', array( 'label' => 'Delete' ) )
        ->getForm()
        ;
    }
}
