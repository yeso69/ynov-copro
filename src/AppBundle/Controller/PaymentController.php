<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Payment;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Debug\Exception\FatalThrowableError;
use Symfony\Component\HttpFoundation\Request;

/**
 * Payment controller.
 *
 * @Route("payment")
 */
class PaymentController extends Controller
{
    /**
     * Lists all payment entities.
     *
     * @Route("/", name="payment_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $payments = $this->getUser()->getPayment();

        return $this->render('payment/index.html.twig', array(
            'payments' => $payments,
        ));
    }

    /**
     * Creates a new payment entity.
     *
     * @Route("/new", name="payment_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $payment = new Payment();
        $form = $this->createForm('AppBundle\Form\PaymentType', $payment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Symfony\Component\HttpFoundation\File\UploadedFile.php $file */

            $file = $payment->getDocument();

            if ($fileName = $request->files->get('appbundle_payment')['document']) {
                $fileName = $request->files->get('appbundle_payment')['document']->getClientOriginalName();
                $file->move(
                    $this->getParameter('documents_directory'),
                    $fileName
                );
                $payment->setDocument($fileName);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($payment);
            $em->flush();
            return $this->redirectToRoute('payment_show', array('id' => $payment->getId()));
        }

        return $this->render('payment/new.html.twig', array(
            'payment' => $payment,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a payment entity.
     *
     * @Route("/{id}", name="payment_show")
     * @Method("GET")
     */
    public function showAction(Payment $payment)
    {
        $deleteForm = $this->createDeleteForm($payment);

        return $this->render('payment/show.html.twig', array(
            'payment' => $payment,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing payment entity.
     *
     * @Route("/{id}/edit", name="payment_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Payment $payment)
    {
        $doc = $payment->getDocument();
        $deleteForm = $this->createDeleteForm($payment);
        $editForm = $this->createForm('AppBundle\Form\PaymentType', $payment);
        $editForm->handleRequest($request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            /** @var Symfony\Component\HttpFoundation\File\UploadedFile.php $file */
            $file = $payment->getDocument();
            if($doc and !$file){
                $file = $doc;
            }
            if ($fileName = $request->files->get('appbundle_payment')['document']) {

                $fileName = $request->files->get('appbundle_payment')['document']->getClientOriginalName();
                $file->move(
                    $this->getParameter('documents_directory'),
                    $fileName
                );

                $payment->setDocument($fileName);

            }else{

                $payment->setDocument($file);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($payment);
            $em->flush();
            return $this->redirectToRoute('payment_edit', array('id' => $payment->getId()));
        }

        return $this->render('payment/edit.html.twig', array(
            'payment' => $payment,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a payment entity.
     *
     * @Route("/{id}/delete", name="payment_delete")
     * @Method("GET")
     */
    public function deleteAction(Request $request, Payment $payment)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($payment);
        $em->flush();
        return $this->redirectToRoute('payment_index');
    }

    /**
     * Creates a form to delete a payment entity.
     *
     * @param Payment $payment The payment entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Payment $payment)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('payment_delete', array('id' => $payment->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
