<?php

namespace App\Controller;

use App\Client\PiecesAutoClient;
use App\Entity\Car;
use App\Repository\CarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    #[Route("/edit/{id}", name: "edit_car")]
    public function createEdit(Request $request, CarRepository $carRepository)
    {
        if ($id = $request->get('id')) {
            $car = $carRepository->find($id);
        } else {
            $car = new Car();
        }

        $form = $this->createForm('App\Form\CarType', $car);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Car $car */
            $car = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $flashMessage = 'Voiture modifiée (' . $car->getLicensePlate() . ')';
            if ($id === null) {
                $em->persist($car);
                $flashMessage = 'Nouvelle voiture ajoutée (' . $car->getLicensePlate() . ')';
            }
            $em->flush();

            $this->addFlash('success', $flashMessage);

            return $this->redirectToRoute('index');
        }

        return $this->render('create_car.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route("/view/{id}", name: "view_car")]
    public function view(Car $car, PiecesAutoClient $piecesAutoClient)
    {
        $carLink = $piecesAutoClient->getCarLink($car);

        return $this->render('view_car.html.twig', [
            'car' => $car,
            'carLink' => $carLink,
        ]);
    }
}