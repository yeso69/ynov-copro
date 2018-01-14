<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Message;
use AppBundle\Entity\Project;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;

/**
 * Project controller.
 *
 * @Route("project")
 */
class ProjectController extends Controller
{
    /**
     * Lists all project entities.
     *
     * @Route("/", name="project_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $projects = $em->getRepository('AppBundle:Project')->findAll();

        return $this->render('project/index.html.twig', array(
            'projects' => $projects,
        ));
    }

    /**
     * Creates a new project entity.
     *
     * @Route("/new", name="project_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $project = new Project($this->getUser());
        $form = $this->createForm('AppBundle\Form\ProjectType', $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $project->setMembers($project->getMembers());
            $this->addDocument($project, $request);

            $em = $this->getDoctrine()->getManager();
            $em->persist($project);
            $em->flush();


            return $this->redirectToRoute('project_show', array('id' => $project->getId()));
        }

        return $this->render('project/new.html.twig', array(
            'project' => $project,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a project entity.
     *
     * @Route("/{id}", name="project_show")
     * @Method("GET")
     */
    public function showAction(Project $project)
    {
        if(!$this->userInProject($project)) {
            return $this->redirectToRoute('project_index');
        }
        $allowedToEdit = $this->currentUserIsCreator($project);
        $message = new Message($this->getUser());
        $form = $this->createForm('AppBundle\Form\MessageType', $message, array(
            'action' => $this->generateUrl('discussion_show', array('id' => $project->getDiscussion()->getId())),
            'method' => 'POST',
        ));

        return $this->render('project/show.html.twig', array(
            'project' => $project,
            'form' => $form->createView(),
            'allowedToEdit' => $allowedToEdit,
        ));
    }

    /**
     * Displays a form to edit an existing project entity.
     *
     * @Route("/{id}/edit", name="project_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Project $project)
    {
        $isMine = $this->currentUserIsCreator($project);
        if($isMine == false){
            $this->addFlash('warning', "You are not allowed to edit this project.");
            return $this->redirectToRoute('project_index');
        }

        $doc = $project->getDocument();
        $editForm = $this->createForm('AppBundle\Form\ProjectType', $project);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $project->setMembers($project->getMembers());

            if($project->getDocument() == null){
                $project->setDocument($doc);
            } else {
                $this->addDocument($project, $request);
            }

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', "Project edited successfully.");
            return $this->redirectToRoute('project_show', array('id' => $project->getId()));
        }

        return $this->render('project/edit.html.twig', array(
            'project' => $project,
            'form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a project entity.
     *
     * @Route("/{id}/delete", name="project_delete")
     * @Method("GET")
     */
    public function deleteAction(Request $request, Project $project)
    {
        if($this->currentUserIsCreator($project) || $this->getUser()->hasRole('ROLE_ADMIN')) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($project);
            $em->flush();
            $this->addFlash('success', "Project deleted successfully.");
        }
        else {
            $this->addFlash('warning', "You are not allowed to delete this project.");
        }
        return $this->redirectToRoute('project_index');
    }

    /**
     * Creates a form to delete a project entity.
     *
     * @param Project $project The project entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Project $project)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('project_delete', array('id' => $project->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    private function currentUserIsCreator(Project $project){
        $isMine = false;
        foreach ($this->getUser()->getCreatedProjects() as $mine){
            if($mine->getId() == $project->getId()){
                $isMine = true;break;
            }
        }
        return $isMine;
    }

    private function userInProject($project)
    {
        $user = $this->getUser();

        $userIsMember = false;
        foreach ($project->getMembers() as $member){
            if($member->getId() == $user->getId()) {
                $userIsMember = true;break;
            }
        }
        if($userIsMember == false){
            $this->addFlash('warning', "Acces denied for this project.");
        }
        return $userIsMember;
    }

    /**
     * Add a file to project entity.
     *
     * @Route("/{id}/add_file", name="project_add_file")
     * @Method("GET")
     */
    public function addFile(Request $request, Project $project){
        $file = $request->get('file');
        $project->addDocument($file);
        $em = $this->getDoctrine()->getManager();
        $em->persist($project);
        $em->flush();
        $this->addFlash('success', "Document added successfully.");
        $referer = $request->headers->get('referer');
        return $this->redirect($referer);
    }

    private function addDocument(Project $project, Request $request)
    {
        $file = $project->getDocument();
        if ($fileName = $request->files->get('appbundle_project')['document']) {
            $fileName = $request->files->get('appbundle_project')['document']->getClientOriginalName();

            $file->move(
                $this->getParameter('uploads_directory'),
                $fileName
            );

            $project->setDocument($fileName);
        }
    }
}
