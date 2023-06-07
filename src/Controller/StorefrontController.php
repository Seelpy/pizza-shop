<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\PizzaServiceInterface;
use App\Service\UserService;
use App\Service\UserServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class StorefrontController extends AbstractController
{
    private PizzaServiceInterface $pizzaService;
    private UserServiceInterface $userService;
    private Environment $twig;

    public function __construct(PizzaServiceInterface $pizzaService, UserServiceInterface $userService)
    {
        $this->pizzaService = $pizzaService;
        $this->userService = $userService;
        $this->twig = new Environment(new FilesystemLoader("../templates"));
    }

    public function showHome(): Response
    {
        $securityUser = $this->getUser();
        $user = $this->userService->findUserByEmail($securityUser->getUserIdentifier());
        $pizzas = $this->pizzaService->listPizza();
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
