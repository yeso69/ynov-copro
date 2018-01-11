<?php
/**
 * Created by PhpStorm.
 * User: mbela
 * Date: 11/01/2018
 * Time: 10:41
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Debug\Exception\UndefinedMethodException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

/**
 * Charge controller.
 * @Route("uploads")
 */
class FileController extends Controller
{
    /**
     * Finds and displays a file
     *
     * @Route("/{entity}/{id}", name="file_show")
     * @Method("GET")
     */
    public function showAction($entity, $id)
    {


        $em = $this->getDoctrine()->getManager();
        $entity= $em->getRepository("AppBundle:".ucfirst($entity))->find($id);
        if ($entity){

            $response = new BinaryFileResponse(realpath($this->getParameter('documents_directory')).DIRECTORY_SEPARATOR.$entity->getDocument());
            $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT);

            return $response;
        }else{
            throw $this->createNotFoundException('The document does not exist');
        }

    }
}