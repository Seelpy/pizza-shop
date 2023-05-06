<?php

declare(strict_types=1);

namespace App\Controller;


use App\Database\ConnectionProvider;
use App\Database\PizzaTable;
use App\Database\UserTable;
use App\View\PhpTemplateEngine;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class StorefrontController extends AbstractController
{
    private const HTTP_STATUS_303_SEE_OTHER = 303;
    private PizzaTable $pizzaTable;
    private UserTable $userTable;
    private Environment $twig;

    public function __construct()
    {
        $connection = ConnectionProvider::connectDatabase();
        $this->pizzaTable = new PizzaTable($connection);
        $this->userTable = new UserTable($connection);
        $this->twig = new Environment(new FilesystemLoader("../templates"));
    }

    public function showHome(): Response
    {
        session_start();
        if (!isset($_SESSION['email']))
        {
            return $this->redirectToRoute("login", [], Response::HTTP_SEE_OTHER);
        }
        $user = $this->userTable->findUser($_SESSION['email']);
        $pizzas = $this->pizzaTable->getAllPizzas();
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
