<?php

namespace App\Controller;

use App\Entity\Pin;
use App\Repository\PinRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PinController extends AbstractController
{
    /**
     * @Route("/pin", name="home")
     * @param PinRepository $pinRepository
     * @return Response
     */
    public function index(PinRepository $pinRepository): Response
    {
        $pins = $pinRepository->findAll();
        return $this->render('pin/index.html.twig', [
            'pins'=> $pins

        ]);
    }

    /**
     * @Route("/pin/{id<[0-9]+>}", name="show_pin")
     * @param Pin $pin
     * @return Response
     */
    public function show(Pin $pin):Response
    {
         return $this->render('pin/show.html.twig', [
             'pin'=> $pin
         ]);
    }
}
