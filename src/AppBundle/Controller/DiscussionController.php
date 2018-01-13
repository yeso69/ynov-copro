<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Discussion;
use AppBundle\Entity\Message;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Discussion controller.
 *
 * @Route("discussion")
 */
class DiscussionController extends Controller
{
    /**
     * Lists all discussion entities.
     *
     * @Route("/", name="discussion_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $discussions = $this->getUser()->getDiscussions();

        return $this->render('discussion/index.html.twig', array(
            'discussions' => $discussions,
        ));
    }

    /**
     * Creates a new discussion entity.
     *
     * @Route("/new", name="discussion_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $discussion = new Discussion($this->getUser());
        $form = $this->createForm('AppBundle\Form\DiscussionType', $discussion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $user = $this->getUser();

            //get discussion and prepare it
            $discussion = $form->getData();
            $discussion->setCreator($user);
            $discussion->setMembers($discussion->getMembers());
            $discussion->setArchived(false);
            $this->addCreatorInMembersIfNotSelected($discussion);

            //create new message
            $message = new Message($user);
            $message->setDiscussion($discussion);
            $content = $form->get('message')->getData();
            $message->setContent($content);

            //persist data
            $em->persist($discussion);
            $em->persist($message);
            $em->flush();

            return $this->redirectToRoute('discussion_show', array('id' => $discussion->getId()));
        }

        return $this->render('discussion/new.html.twig', array(
            'discussion' => $discussion,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a discussion entity.
     *
     * @Route("/{id}", name="discussion_show")
     * @Method({"GET", "POST"})
     */
    public function showAction(Discussion $discussion, Request $request)
    {
        if(!$this->userInDiscussion($discussion,$request)) {
            return $this->redirectToRoute('discussion_index');
        }
        $em = $this->getDoctrine()->getManager();
        $message = new Message($this->getUser());
        $form = $this->createForm('AppBundle\Form\MessageType', $message);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $message->setDiscussion($discussion);
            $em->persist($message);
            $em->flush();
        }

        $deleteForm = $this->createDeleteForm($discussion);

        return $this->render('discussion/show.html.twig', array(
            'discussion' => $discussion,
            'delete_form' => $deleteForm->createView(),
            'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing discussion entity.
     *
     * @Route("/{id}/edit", name="discussion_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Discussion $discussion)
    {
        if ($discussion->getArchived()){
            $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');
        }
        $this->isMineOrRedirect($discussion);

        $editForm = $this->createForm('AppBundle\Form\DiscussionEditType', $discussion);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->addCreatorInMembersIfNotSelected($discussion);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', "Discussion edited successfuly.");
            return $this->redirectToRoute('discussion_show', array('id' => $discussion->getId()));
        }

        return $this->render('discussion/edit.html.twig', array(
            'discussion' => $discussion,
            'form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a discussion entity.
     *
     * @Route("/{id}/delete", name="discussion_delete")
     * @Method("GET")
     */
    public function deleteAction(Request $request, Discussion $discussion)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($discussion);
        $em->flush();

        return $this->redirectToRoute('discussion_index');
    }

    /**
     * Creates a form to delete a discussion entity.
     *
     * @param Discussion $discussion The discussion entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Discussion $discussion)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('discussion_delete', array('id' => $discussion->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    private function userInDiscussion(Discussion $discussion, Request $request){
        $user = $this->getUser();

        $userIsMember = false;
        foreach ($discussion->getMembers() as $member){
            if($member->getId() == $user->getId()) {
                $userIsMember = true;break;
            }
        }
        if($userIsMember == false){
            $this->addFlash('warning', "Acces denied for this discussion.");
        }
        return $userIsMember;
    }

    private function addCreatorInMembersIfNotSelected(Discussion $discussion)
    {
        $user = $this->getUser();

        //if user selected do not add in members
        $members = $discussion->getMembers();
        $isSelected = false;
        foreach ($members as $member){
            if($member->getId() == $user->getId()) {
                $isSelected = true;break;
            }
        }
        if(!$isSelected){$discussion->addMember($user);}
    }

    private function isMineOrRedirect(Discussion $discussion){
        $isMine = false;
        foreach ($this->getUser()->getCreatedDiscussions() as $mine){
            if($mine->getId() == $discussion->getId()){
                $isMine = true;break;
            }
        }
        if($isMine == false){
            $this->addFlash('warning', "You are not allowed to edit this discussion.");
            return $this->redirectToRoute('discussion_index');
        }
    }
}
