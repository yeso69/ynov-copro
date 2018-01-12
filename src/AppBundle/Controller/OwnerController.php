<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Owner;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Owner controller.
 *
 * @Route("owner")
 */
class OwnerController extends Controller
{
    /**
     * Lists all owner entities.
     *
     * @Route("/", name="owner_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $owners = $em->getRepository('AppBundle:Owner')->findAll();

        return $this->render('owner/index.html.twig', array(
            'owners' => $owners,
        ));
    }

    /**
     * Creates a new owner entity.
     *
     * @Route("/new", name="owner_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $owner = new Owner();
        $form = $this->createForm('AppBundle\Form\OwnerType', $owner);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($owner);
            $em->flush();

            return $this->redirectToRoute('owner_show', array('id' => $owner->getId()));
        }

        return $this->render('owner/new.html.twig', array(
            'owner' => $owner,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a owner entity.
     *
     * @Route("/{id}", name="owner_show")
     * @Method("GET")
     */
    public function showAction(Owner $owner)
    {
        $deleteForm = $this->createDeleteForm($owner);

        return $this->render('owner/show.html.twig', array(
            'owner' => $owner,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing owner entity.
     *
     * @Route("/{id}/edit", name="owner_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Owner $owner)
    {
        $deleteForm = $this->createDeleteForm($owner);
        $editForm = $this->createForm('AppBundle\Form\OwnerType', $owner);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('owner_edit', array('id' => $owner->getId()));
        }

        return $this->render('owner/edit.html.twig', array(
            'owner' => $owner,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a owner entity.
     *
     * @Route("/{id}", name="owner_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Owner $owner)
    {
        $form = $this->createDeleteForm($owner);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($owner);
            $em->flush();
        }

        return $this->redirectToRoute('owner_index');
    }

    /**
     * Creates a form to delete a owner entity.
     *
     * @param Owner $owner The owner entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Owner $owner)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('owner_delete', array('id' => $owner->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
