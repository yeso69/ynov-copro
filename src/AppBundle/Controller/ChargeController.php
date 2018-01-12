<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Charge;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;

/**
 * Charge controller.
 *
 * @Route("charge")
 */
class ChargeController extends Controller
{
    /**
     * Lists all charge entities.
     *
     * @Route("/", name="charge_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $charges = $em->getRepository('AppBundle:Charge')->findAll();

        return $this->render('charge/index.html.twig', array(
            'charges' => $charges,
        ));
    }

    /**
     * Creates a new charge entity.
     *
     * @Route("/new", name="charge_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $charge = new Charge();
        $form = $this->createForm('AppBundle\Form\ChargeType', $charge);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $charge->getDocument();
            if(count($form->getNormData()->getConcernedOwners())== 0){
                $charge->setConcernedOwners(
                    $this->getDoctrine()->getManager()->getRepository('AppBundle:Owner')->findAll()
                );
            }
            if ($fileName = $request->files->get('appbundle_charge')['document']) {
                $fileName = $request->files->get('appbundle_charge')['document']->getClientOriginalName();
                $file->move(
                    $this->getParameter('documents_directory'),
                    $fileName
                );
                $charge->setDocument($fileName);
                try{
                    $file->move(
                        $this->getParameter('uploads_directory'),
                        $fileName
                    );
                }catch (FileException $e){}

                $charge->setDocument($fileName);
            }




            $em = $this->getDoctrine()->getManager();
            $em->persist($charge);
            $em->flush();

            return $this->redirectToRoute('charge_show', array('id' => $charge->getId()));
        }

        return $this->render('charge/new.html.twig', array(
                'charge' => $charge,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a charge entity.
     *
     * @Route("/{id}", name="charge_show")
     * @Method("GET")
     */
    public function showAction(Charge $charge)
    {
        $deleteForm = $this->createDeleteForm($charge);

        return $this->render('charge/show.html.twig', array(
            'charge' => $charge,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing charge entity.
     *
     * @Route("/{id}/edit", name="charge_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Charge $charge)
    {
        try{
            $charge->setDocument(
                new File($this->getParameter('uploads_directory').'/'.$charge->getDocument())
            );
        }catch (\Exception $e){

        }

        $deleteForm = $this->createDeleteForm($charge);
        $editForm = $this->createForm('AppBundle\Form\ChargeType', $charge);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('charge_edit', array('id' => $charge->getId()));
        }

        return $this->render('charge/edit.html.twig', array(
            'charge' => $charge,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a charge entity.
     * @Method("GET")
     * @Route("/{id}/delete", name="charge_delete")

     */
    public function deleteAction(Request $request, Charge $charge)
    {

        $em = $this->getDoctrine()->getManager();
        $em->remove($charge);
        $em->flush();
        return $this->redirectToRoute('charge_index');
    }

    /**
     * Creates a form to delete a charge entity.
     *
     * @param Charge $charge The charge entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Charge $charge)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('charge_delete', array('id' => $charge->getId())))
            ->setMethod('GET')
            ->getForm()
        ;
    }
}
