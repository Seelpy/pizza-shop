<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\UserServiceInterface;
use App\Service\ImageServiceInterface;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class UserController extends AbstractController
{
    private UserServiceInterface $userService;
    private ImageServiceInterface $imageService;
    private Environment $twig;

    public function __construct(UserServiceInterface $userService, ImageServiceInterface $imageService)
    {
        $this->userService = $userService;
        $this->imageService = $imageService;
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
        session_start();

        $imageData = $_FILES;
        $avatar_path = null;
        if (isset($imageData["avatar"]) & $imageData["avatar"]["name"] != "") {
            $avatar_path = $this->imageService->moveImageToUploads($imageData["avatar"]);
        }
        
        $user = new User(
            id: null,
            email: $request->get("email"),
            password: $request->get("password"),
            name: $request->get("name"),
            lastname: $request->get("lastname"),
            address: $request->get("address"),
            avatarPath: $avatar_path,
        );

        if (!$this->isCorrectRegistrationData($user)) {
            return $this->redirectToRoute("registration", [], Response::HTTP_SEE_OTHER);
        }

        $_SESSION['email'] = $user->getEmail();
        $this->userService->register($user);

        return $this->redirectToRoute("show_home", [], Response::HTTP_SEE_OTHER);
    }

    public function loginUser(Request $request): Response
    {
        session_start();

        $userEmail = $request->get("email");
        $userPassword = $request->get("password");

        if (!$this->userService->isCorrectLoginData($userEmail, $userPassword)) {
            return $this->redirectToRoute("login", [], Response::HTTP_SEE_OTHER);
        }

        $_SESSION["email"] = $userEmail;
        return $this->redirectToRoute("show_home", [], Response::HTTP_SEE_OTHER);
    }

    private function isCorrectRegistrationData(User $user): bool
    {
        $email = $user->getEmail();
        if ($email === null) {
            return false;
        }
        $emailValidationRegex = "/^\\S+@\\S+\\.\\S+$/";
        if (!preg_match($emailValidationRegex, $email)) {
            return false;
        }
        return true;
    }
}
