<?php
/**
 * LinkController.php file
 *
 * File that contains the page link controller class
 *
 * Licence MIT
 * Copyright (c) 2014 Multnomah Education Service District <http://www.mesd.k12.or.us>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @filesource /src/Mesd/HelpWikiBundle/Controller/LinkController.php
 * @package    Mesd\HelpWikiBundle\Controller
 * @copyright  2014 (c) Multnomah Education Service District <http://www.mesd.k12.or.us>
 * @license    <http://opensource.org/licenses/MIT> MIT
 * @author     Curtis G Hanson <chanson@mesd.k12.or.us>
 * @version    0.1.0
 */
namespace Mesd\HelpWikiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\Constraints\NotBlank;

use Mesd\HelpWikiBundle\Entity\Link;
use Mesd\HelpWikiBundle\Form\LinkType;

/**
 * Link controller.
 *
 */
class LinkController extends Controller
{

    /**
     * Lists all Link entities.
     *
     * @return unknown
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository( 'MesdHelpWikiBundle:Link' )->findAll();

        return $this->render( 'MesdHelpWikiBundle:Link:index.html.twig', array(
                'entities' => $entities,
            )
        );
    }

    /**
     * Creates a new Link entity.
     *
     * @param object  $request
     * @return unknown
     */
    public function createAction( Request $request )
    {
        $entity = new link();
        $form = $this->createCreateForm( $entity );
        $form->handleRequest( $request );

        if ( $form->isValid() ) {
            $em = $this->getDoctrine()->getManager();
            $em->persist( $entity );
            $em->flush();

            return $this->redirect( $this->generateUrl( 'link_show', array( 'id' => $entity->getId())));
        }

        return $this->render( 'MesdHelpWikiBundle:Link:new.html.twig', array(
                'entity' => $entity,
                'form'   => $form->createView(),
            )
        );
    }

    /**
     * Creates a form to create a Link entity.
     *
     *
     * @param object  $entity The entity
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm( Link $entity )
    {
        $form = $this->createForm( new LinkType(), $entity, array(
                'action' => $this->generateUrl( 'link_create' ),
                'method' => 'POST',
            )
        );

        $form->add( 'submit', 'submit' );

        return $form;
    }

    /**
     * Creates a form to create a Page entity.
     *
     * @param Link    $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreatePageForm()
    {
        $form = $this->createFormBuilder()
        ->setAction( $this->generateUrl( 'link_new_by_page' ) )
        ->add( 'routeAlias', 'hidden', array(
                'constraints' => array(
                    new NotBlank(),
                ),
            ) )
        ->add( 'submit', 'submit' )
        ->getForm();

        return $form;
    }

    /**
     * Displays a form to create a new Link entity.
     *
     * @return unknown
     */
    public function newAction()
    {
        $entity = new link();
        $form   = $this->createCreateForm( $entity );

        return $this->render( 'MesdHelpWikiBundle:Link:new.html.twig', array(
                'entity' => $entity,
                'form'   => $form->createView(),
            )
        );
    }

    /**
     * Finds and displays a Link entity.
     *
     * @param unknown $id
     * @return unknown
     */
    public function showAction( $id )
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository( 'MesdHelpWikiBundle:Link' )->find( $id );

        if ( !$entity ) {
            throw $this->createNotFoundException( 'Unable to find Link entity.' );
        }

        $deleteForm = $this->createDeleteForm( $id );

        return $this->render( 'MesdHelpWikiBundle:Link:show.html.twig', array(
                'entity'      => $entity,
                'delete_form' => $deleteForm->createView(),
            )
        );
    }

    /**
     * Displays a form to edit an existing Link entity.
     *
     * @param unknown $id
     * @return unknown
     */
    public function editAction( $id )
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository( 'MesdHelpWikiBundle:Link' )->find( $id );

        if ( !$entity ) {
            throw $this->createNotFoundException( 'Unable to find Link entity.' );
        }

        $editForm = $this->createEditForm( $entity );
        $deleteForm = $this->createDeleteForm( $id );

        return $this->render( 'MesdHelpWikiBundle:Link:edit.html.twig', array(
                'entity'      => $entity,
                'edit_form'   => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            )
        );
    }

    /**
     * Creates a form to edit a Link entity.
     *
     *
     * @param object  $entity The entity
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm( Link $entity )
    {
        $form = $this->createForm( new LinkType(), $entity, array(
                'action' => $this->generateUrl( 'link_update', array( 'id' => $entity->getId() ) ),
                'method' => 'PUT',
            )
        );

        $form->add( 'submit', 'submit', array( 'label' => 'Update'        )
        );

        return $form;
    }

    /**
     * Edits an existing Link entity.
     *
     * @param object  $request
     * @param unknown $id
     * @return unknown
     */
    public function updateAction( Request $request, $id )
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository( 'MesdHelpWikiBundle:Link' )->find( $id );

        if ( !$entity ) {
            throw $this->createNotFoundException( 'Unable to find Link entity.' );
        }

        $deleteForm = $this->createDeleteForm( $id );
        $editForm = $this->createEditForm( $entity );
        $editForm->handleRequest( $request );

        if ( $editForm->isValid() ) {
            $em->flush();

            return $this->redirect( $this->generateUrl( 'link_edit', array( 'id' => $id )));
        }

        return $this->render( 'MesdHelpWikiBundle:Link:edit.html.twig', array(
                'entity'      => $entity,
                'edit_form'   => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            )
        );
    }

    /**
     * Deletes a Link entity.
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
            $entity = $em->getRepository( 'MesdHelpWikiBundle:Link' )->find( $id );

            if ( !$entity ) {
                throw $this->createNotFoundException( 'Unable to find Link entity.' );
            }

            $em->remove( $entity );
            $em->flush();
        }

        return $this->redirect( $this->generateUrl( 'link'        )
        );
    }

    /**
     * Creates a form to delete a Link entity by id.
     *
     *
     * @param mixed   $id The entity id
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm( $id )
    {
        return $this->createFormBuilder()
        ->setAction( $this->generateUrl( 'link_delete', array( 'id' => $id ) ) )
        ->setMethod( 'DELETE' )
        ->add( 'submit', 'submit', array( 'label' => 'Delete' ) )
        ->getForm()
        ;
    }

    /**
     *
     *
     * @param unknown $route
     * @return unknown
     */
    public function isLinkedAction($route)
    {
        $em = $this->getDoctrine()->getManager();

        $link = $em->getRepository('MesdHelpWikiBundle:Link')->findOneByRouteAlias($route);

        $show = $this->container->getParameter('mesd_help_wiki.show_no_help');

        if (!$link) {
            return $this->render('MesdHelpWikiBundle:Link:linker.html.twig', array(
                    'route'        => $route,
                    'show_no_help' => $show,
                )
            );
        }
        return $this->render('MesdHelpWikiBundle:Link:linker.html.twig', array(
                'route'        => $route,
                'link'         => $link,
                'show_no_help' => $show,
            )
        );
    }

    /**
     *
     *
     * @param unknown $route
     * @return unknown
     */
    public function newByRouteAction( $route )
    {
        $entity = new link();
        $newLinkForm = $this->createCreateForm( $entity );
        $newPageForm = $this->createCreatePageForm();

        $newLinkForm->get( 'routeAlias' )->setData( $route );
        $newPageForm->get( 'routeAlias' )->setData( $route );

        return $this->render( 'MesdHelpWikiBundle:Link:newWithPage.html.twig', array(
                'entity'        => $entity,
                'new_link_form' => $newLinkForm->createView(),
                'new_page_form' => $newPageForm->createView(),
            )
        );
    }

    /**
     *
     *
     * @param object  $request
     * @return unknown
     */
    public function createNewLinkPageAction( Request $request )
    {
        $form = $this->createCreatePageForm();
        $form->handleRequest( $request );

        if ( $form->isValid() ) {
            // data is an array with "name", "email", and "message" keys
            $data = $form->getData();

            $routeAlias = $data['routeAlias'];

            return $this->redirect( $this->generateUrl( 'page_new', array( 'routeAlias' => $routeAlias)));
        }
    }
}
