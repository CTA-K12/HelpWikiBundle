<?php

namespace Mesd\HelpWikiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Mesd\HelpWikiBundle\Entity\Page;
use Mesd\HelpWikiBundle\Form\PageType;

use Mesd\HelpWikiBundle\Entity\Comment;
use Mesd\HelpWikiBundle\Form\CommentType;

/**
 * Page controller.
 *
 */
class PageController extends Controller
{

    /**
     * Lists all Page entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MesdHelpWikiBundle:Page')->findByParent(null);

        usort($entities, array($this, 'cmp_obj'));
        //$child = $entities[1]->getChildren()->toArray();
        //var_dump($child);die;
        return $this->render('MesdHelpWikiBundle:Page:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Page entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Page();

        $form = $this->createCreateForm($entity);

        $form->add('routeAlias', 'hidden', array(
            'mapped' => false,
        ));

        //echo '<pre>';var_dump($request);exit;
        $form->handleRequest($request);

        if ($form->isValid()) {
            $routeAlias = $form->get('routeAlias')->getViewData();
            $this->container->set('routeAlias', $routeAlias);

            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('page_show', array('slug' => $entity->getSlug())));
        }

        return $this->render('MesdHelpWikiBundle:Page:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a Page entity.
    *
    * @param Page $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Page $entity)
    {
        $form = $this->createForm(new PageType($entity), $entity, array(
            'action' => $this->generateUrl('page_create'),
            'method' => 'POST',
        ));

        $form->add('save', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Page entity.
     *
     */
    public function newAction()
    {
        $entity = new Page();
        $form   = $this->createCreateForm($entity);

        $data = $this->getRequest()->query->get('routeAlias');

        if($data){
            $form->add('routeAlias', 'hidden', array(
                'mapped' => false,
                'data' => $data
            ));
        }

        return $this->render('MesdHelpWikiBundle:Page:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Page entity.
     *
     */
    public function showAction($slug)
    {
        $em = $this->getDoctrine()->getManager();

        $entity   = $em->getRepository('MesdHelpWikiBundle:Page')->findOneBySlug($slug);
        $comments = $em->getRepository('MesdHelpWikiBundle:Comment')->findByPage($entity->getId());
        $next   = $em->getRepository('MesdHelpWikiBundle:Page')->getNextPage($entity);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Page entity.');
        }

        $deleteForm = $this->createDeleteForm($entity->getId());

        $title = preg_replace('/\s*?\bpages?\b\s*?$/i', '', $entity->getTitle());


        return $this->render('MesdHelpWikiBundle:Page:show.html.twig', array(
            'subtitle'    => $title,
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
            'comments'    => $comments,
            'next'        => $next,
        ));
    }

    /**
     * Displays a form to edit an existing Page entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MesdHelpWikiBundle:Page')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Page entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        $title = 'Edit ' . preg_replace('/\s*?\bpages?\b\s*?$/i', '', $entity->getTitle()) . ' Page';

        return $this->render('MesdHelpWikiBundle:Page:edit.html.twig', array(
            'subtitle'    => $title,
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Page entity.
    *
    * @param Page $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Page $entity)
    {

        $form = $this->createForm(new PageType($entity), $entity, array(
            'action' => $this->generateUrl('page_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('save', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Page entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MesdHelpWikiBundle:Page')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Page entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('page_show', array('slug' => $entity->getSlug())));
        }

        return $this->render('MesdHelpWikiBundle:Page:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Page entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MesdHelpWikiBundle:Page')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Page entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('page'));
    }

    /**
     * Creates a form to delete a Page entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('page_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }

    /**
     * Displays a form to reorder all Page entities.
     *
     */
    public function editOrderAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MesdHelpWikiBundle:Page')->findByParent(null);

        usort($entities, array($this, 'cmp_obj'));

        return $this->render('MesdHelpWikiBundle:Page:editOrder.html.twig', array(
            'entities' => $entities,
        ));
    }

    /* This is the static comparing function: */
    static function cmp_obj($a, $b)
    {
        $al = strtolower($a->getPrintOrder());
        $bl = strtolower($b->getPrintOrder());
        if ($al == $bl) {
            $ax = strtolower($a->getTitle());
            $bx = strtolower($b->getTitle());
            if ($ax == $bx) {
                return 0;
            }
            return ($ax > $bx) ? +1 : -1;
        }
        return ($al > $bl) ? +1 : -1;
    }

}