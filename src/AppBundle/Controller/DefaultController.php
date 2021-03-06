<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        //$this->denyAccessUnlessGranted('ROLE_USER', null, 'Unable to access this page!');
        $em = $this->getDoctrine()->getManager();
        $charges = $em->getRepository("AppBundle:Charge")->findBy(['status'=>false]);
        $discussions = $this->getUser()->getDiscussions();
        $payments = $em->getRepository('AppBundle:Payment')->findBy(['owner'=>$this->getUser(), 'done'=>false]);
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            'charges'=>$charges,
            'discussions'=>$discussions,
            'payments'=>$payments
        ]);
    }
}
