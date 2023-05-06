<?php
declare(strict_types=1);

namespace App\Controller;


use App\Database\ConnectionProvider;
use App\Database\PizzaTable;
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
    private Environment $twig;

    public function __construct()
    {
        $connection = ConnectionProvider::connectDatabase();
        $this->pizzaTable = new PizzaTable($connection);
        $this->twig = new Environment(new FilesystemLoader("../templates"));
    }

    public function showHome(): Response
    {
        $pizzas = $this->pizzaTable->getAllPizzas();
        $contents = $this->twig->render("./pages/home.html.twig", [
            "sections" => [
                [
                    "section_name" => "hit",
                    "pizzas" => $pizzas
                ]
            ]
        ]);
        return new Response($contents);
    }

    private function writeRedirectSeeOther(string $url): void
    {
        header("Location: " . $url, true, self::HTTP_STATUS_303_SEE_OTHER);
    }

}