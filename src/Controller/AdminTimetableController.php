<?php

namespace App\Controller;

use App\Entity\Timetable;
use App\Form\TimetableType;
use App\Repository\TimetableRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/agenda")
 */
class AdminTimetableController extends AbstractController
{
    /**
     * @Route("/", name="timetable_index", methods={"GET"})
     * @IsGranted("ROLE_ADMIN_JOIN_US", message = "Vous ne passerez pas!")
     */
    public function index(TimetableRepository $timetableRepository): Response
    {
        return $this->render('admin_timetable/index.html.twig', [
            'timetables' => $timetableRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="timetable_new", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN_JOIN_US", message = "Vous ne passerez pas!")
     */
    public function new(Request $request): Response
    {
        $timetable = new Timetable();
        $form = $this->createForm(TimetableType::class, $timetable);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($timetable);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Votre réunion d\'information a été ajoutée'
            );

            return $this->redirectToRoute('timetable_index');
        }

        return $this->render('admin_timetable/new.html.twig', [
            'admin_timetable' => $timetable,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/editer", name="timetable_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN_JOIN_US", message = "Vous ne passerez pas!")
     */
    public function edit(Request $request, Timetable $timetable): Response
    {
        $form = $this->createForm(TimetableType::class, $timetable);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'success',
                'Votre réunion d\'information a été modifiée'
            );

            return $this->redirectToRoute('timetable_index');
        }

        return $this->render('admin_timetable/edit.html.twig', [
            'timetable' => $timetable,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="timetable_delete", methods={"DELETE"})
     * @IsGranted("ROLE_ADMIN_JOIN_US", message = "Vous ne passerez pas!")
     */
    public function delete(Request $request, Timetable $timetable): Response
    {
        if ($this->isCsrfTokenValid('delete'.$timetable->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($timetable);
            $entityManager->flush();
        }

        return $this->redirectToRoute('timetable_index');
    }
}
