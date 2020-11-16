<?php

namespace App\Controller;

use App\Entity\Pin;
use App\Form\PinType;
use App\Repository\PinRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PinController extends AbstractController
{
    /**
     * @Route("/pin", name="home", methods="GET")
     * @param PinRepository $pinRepository
     * @return Response
     */
    public function index(PinRepository $pinRepository): Response
    {
        $pins = $pinRepository->findBy([], [
            'createdAt' => 'DESC'
        ]);
        return $this->render('pin/index.html.twig', [
            'pins'=> $pins

        ]);
    }

    /**
     * @Route("/pin/create", name="create_pin", methods={"GET", "POST"})
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $em) :Response
    {
        $pin = new Pin();

        $form = $this->createForm(PinType::class, $pin);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $pin = $form->getData();
            $em->persist($pin);
            $em->flush();
            return $this->redirectToRoute('home');
        }

        return $this->render('pin/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/pin/{id<[0-9]+>}", name="show_pin", methods="GET")
     * @param Pin $pin
     * @return Response
     */
    public function show(Pin $pin):Response
    {
         return $this->render('pin/show.html.twig', [
             'pin'=> $pin
         ]);
    }

    /**
     * @Route("/pin/{id<[0-9]+>}/edit", name="edit_pin", methods={"GET", "PUT"})
     * @param Pin $pin
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     */

    public function edit(Pin $pin, Request $request, EntityManagerInterface $em) :Response
    {


        $form = $this->createForm(PinType::class, $pin, [
            'method'=> 'PUT'
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $pin = $form->getData();
            $em->persist($pin);
            $em->flush();
            return $this->redirectToRoute('home');
        }

        return $this->render('pin/edit.html.twig', [
            'pin'=> $pin,
            'form' => $form->createView(),

        ]);
    }

    /**
     * @Route("/pin/{id<[0-9]+>}/delete", name="delete_pin", methods={"DELETE"})
     * @param Request $request
     * @param Pin $pin
     * @param EntityManagerInterface $em
     * @return Response
     */

    public function delete(Request $request, Pin $pin, EntityManagerInterface $em) :Response
    {

        if ($this->isCsrfTokenValid('pin_deletion' . $pin->getId(),$request->request->get('csrf_token') )) {
            $em->remove($pin);
            $em->flush();
        }

      return $this->redirectToRoute('home');
    }
}
