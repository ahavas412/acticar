<?php

namespace App\Controller;

use App\Entity\Car;
use App\Repository\CarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CarController extends AbstractController
{
    #[Route("/", name: "index")]
    public function index(CarRepository $carRepository)
    {
        $cars = $carRepository->findAll();

        return $this->render("base.html.twig", [
            'cars' => $cars,
        ]);
    }

    #[Route("/create", name: "create_car")]
    public function create()
    {
        $form = 0;

        return $this->render('create_car.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route("/view/{id}", name: "view_car")]
    public function view(Car $car)
    {
        return $this->render('view_car.html.twig', [
            'car' => $car,
        ]);
    }
}