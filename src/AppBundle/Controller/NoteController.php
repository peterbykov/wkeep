<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Note;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class NoteController extends Controller
{
    public function indexAction() {

    }

    /**
     * @Route("/note", name="note_get_all")
     * @Method("GET")
     */
    public function getAllAction() {
        $noteRepository = $this->getDoctrine()->getManager()->getRepository(Note::class);
        $notes = $noteRepository->findAll();
        $notesNormalized = $this->get('serializer')->normalize($notes);
        return new JsonResponse($notesNormalized);
    }

    /**
     * @Route("/note", name="note_create")
     * @Method("POST")
     * @param Request $request
     * @return JsonResponse
     */
    public function createAction(Request $request) {
        $note = new Note();
        $note->setTitle($request->get('title'));
        $note->setContent($request->get('content'));
        $em = $this->getDoctrine()->getManager();
        $em->persist($note);
        $em->flush();
        $noteNormalized = $this->get('serializer')->normalize($note);
        return new JsonResponse($noteNormalized);
    }

    /**
     * @Route("/note/{id}", name="note_update")
     * @Method("PUT")
     */
    public function updateAction(Request $request, Note $note) {
        $payload = json_decode($request->getContent(),true);

        $note->setTitle($request->get('title'));
        $note->setContent($request->get('content'));

        $em = $this->getDoctrine()->getManager();
        $em->persist($note);
        $em->flush();

        $noteNormalized = $this->get('serializer')->normalize($note);
        return new JsonResponse($noteNormalized);
    }

    /**
     * @Route("/note/{id}", name="note_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Note $note) {
        $em = $this->getDoctrine()->getManager();

        $em->remove($note);
        $em->flush();

        return new JsonResponse([]);
    }
}
