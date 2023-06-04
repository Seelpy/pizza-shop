<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\PizzaRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class StorefrontController extends AbstractController
{
    private PizzaRepository $pizzaRepository;
    private UserRepository $userRepository;
    private Environment $twig;

    public function __construct(PizzaRepository $pizzaRepository, UserRepository $userRepository)
    {
        $this->pizzaRepository = $pizzaRepository;
        $this->userRepository = $userRepository;
        $this->twig = new Environment(new FilesystemLoader("../templates"));
    }

    public function showHome(): Response
    {
        session_start();
        if (!isset($_SESSION['email']))
        {
            return $this->redirectToRoute("login", [], Response::HTTP_SEE_OTHER);
        }
        $user = $this->userRepository->findByEmail($_SESSION['email']);
        $pizzas = $this->pizzaRepository->listAll();
        $contents = $this->twig->render("./pages/home.html.twig", [

            "sections" => [
                [
                    "section_name" => "hit",
                    "pizzas" => $pizzas
                ]
            ],
            "user" => $user,
        ]);
        return new Response($contents);
    }
}
