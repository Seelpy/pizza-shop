<?php
declare(strict_types=1);

namespace App\Controller;

use App\Database\ConnectionProvider;
use App\Database\UserTable;
use App\Model\User;
use App\Uploader\Uploader;
use App\View\PhpTemplateEngine;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class UserController extends AbstractController
{
    private const HTTP_STATUS_303_SEE_OTHER = 303;
    private UserTable $userTable;   
    private Uploader $uploader;
    private Environment $twig;

    public function __construct()
    {
        $connection = ConnectionProvider::connectDatabase();
        $this->userTable = new UserTable($connection);
        $this->uploader = new Uploader();
        $this->twig = new Environment(new FilesystemLoader("../templates"));
    }

    public function showLoginForm(): Response
    {
        $contents = $this->twig->render("./form/login.html.twig", []);
        return new Response($contents);
    }

    public function showRegistrationForm(): Response
    {
        $contents = $this->twig->render("./form/registration.html.twig", []);
        return new Response($contents);
    }

    public function registerUser(Request $request): Response
    {
        $requestData = $_POST;
        $imageData = $_FILES;
        if ($_SERVER["REQUEST_METHOD"] !== "POST")
        {
            $this->writeRedirectSeeOther("/");
            return new Response();
        }

        $avatar_path = null;
        if (isset($imageData["avatar"]) & $imageData["avatar"]["name"] != "")
        {
            $avatar_path = $this->uploader->moveAvatarToUploads($imageData["avatar"]);
        }
        $user = new User(
            email: $requestData["email"],
            password: $requestData["password"],
            name: $requestData["name"],
            lastname: $requestData["lastname"],
            address: $requestData["address"],
            avatarPath: $avatar_path,
        );

        $this->userTable->saveUser($user);

        return $this->redirectToRoute("show_home", [], Response::HTTP_SEE_OTHER);
    }

    public function loginUser(Request $request): Response
    {
        return $this->redirectToRoute("show_home", [], Response::HTTP_SEE_OTHER);
    }

    private function writeRedirectSeeOther(string $url): void
    {
        header("Location: " . $url, true, self::HTTP_STATUS_303_SEE_OTHER);
    }

}