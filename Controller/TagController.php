<?php
/**
 * tagController.php file
 *
 * File that contains the tag controller class
 *
 * Licence MIT
 * Copyright (c) 2014 Multnomah Education Service District <http://www.mesd.k12.or.us>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * @filesource /src/Mesd/HelpWikiBundle/Controller/TagController.php
 * @package    Mesd\HelpWikiBundle\Controller
 * @copyright  2014 (c) Multnomah Education Service District <http://www.mesd.k12.or.us>
 * @license    <http://opensource.org/licenses/MIT> MIT
 * @author     Curtis G Hanson <chanson@mesd.k12.or.us>
 * @version    {@inheritdoc}
 */
namespace Mesd\HelpWikiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Mesd\HelpWikiBundle\Entity\Tag;
use Mesd\HelpWikiBundle\Form\TagType;
use Mesd\HelpWikiBundle\Model\Menu;

/**
 * Tag controller.
 *
 */
class TagController extends Controller
{

    /**
     * Lists all Tag entities.
     *
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MesdHelpWikiBundle:Tag')->findAll();
        //$tag = $entities[0];
        //var_dump(get_class_methods($tag->getPages()));exit;

        return $this->render('MesdHelpWikiBundle:Tag:list.html.twig', array(
            'entities' => $entities,
            'menu'     => new Menu(),
        ));
    }
    /**
     * Creates a new Tag entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Tag();

        if (false === $this->get('security.context')->isGranted('CREATE', $entity)) {
            throw new AccessDeniedException('Unauthorized access!');
        }

        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('MesdHelpWikiBundle_tag_view', array('id' => $entity->getId())));
        }

        return $this->render('MesdHelpWikiBundle:Tag:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'menu'   => new Menu(),
        ));
    }

    /**
    * Creates a form to create a Tag entity.
    *
    * @param Tag $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Tag $entity)
    {
        $form = $this->createForm(new TagType(), $entity, array(
            'action' => $this->generateUrl('MesdHelpWikiBundle_tag_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Tag entity.
     *
     */
    public function newAction()
    {
        $entity = new Tag();

        if (false === $this->get('security.context')->isGranted('CREATE', $entity)) {
            throw new AccessDeniedException('Unauthorized access!');
        }

        $form   = $this->createCreateForm($entity);

        return $this->render('MesdHelpWikiBundle:Tag:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'menu'   => new Menu(),
        ));
    }

    /**
     * Finds and displays a Tag entity.
     *
     */
    public function viewAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MesdHelpWikiBundle:Tag')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tag entity.');
        }

        if (false === $this->get('security.context')->isGranted('VIEW', $entity)) {
            throw new AccessDeniedException('Unauthorized access!');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MesdHelpWikiBundle:Tag:view.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
            'menu'        => new Menu(),
        ));
    }

    /**
     * Displays a form to edit an existing Tag entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MesdHelpWikiBundle:Tag')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tag entity.');
        }

        if (false === $this->get('security.context')->isGranted('EDIT', $entity)) {
            throw new AccessDeniedException('Unauthorized access!');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MesdHelpWikiBundle:Tag:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'menu'        => new Menu(),
        ));
    }

    /**
    * Creates a form to edit a Tag entity.
    *
    * @param Tag $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Tag $entity)
    {
        $form = $this->createForm(new TagType(), $entity, array(
            'action' => $this->generateUrl('MesdHelpWikiBundle_tag_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Tag entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MesdHelpWikiBundle:Tag')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tag entity.');
        }

        if (false === $this->get('security.context')->isGranted('EDIT', $entity)) {
            throw new AccessDeniedException('Unauthorized access!');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('MesdHelpWikiBundle_tag_edit', array('id' => $id)));
        }

        return $this->render('MesdHelpWikiBundle:Tag:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'menu'        => new Menu(),
        ));
    }

    /**
     * Deletes a Tag entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MesdHelpWikiBundle:Tag')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Tag entity.');
            }

            if (false === $this->get('security.context')->isGranted('DELETE', $entity)) {
            throw new AccessDeniedException('Unauthorized access!');
        }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('MesdHelpWikiBundle_tag_list'));
    }

    /**
     * Creates a form to delete a Tag entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this
            ->createFormBuilder()
            ->setAction($this->generateUrl('MesdHelpWikiBundle_tag_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
