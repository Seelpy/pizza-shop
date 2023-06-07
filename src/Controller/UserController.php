<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\UserServiceInterface;
use App\Service\ImageServiceInterface;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;
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
            role: 0,
        );

        if (!$this->isCorrectRegistrationData($user)) {
            return $this->redirectToRoute("registration", [], Response::HTTP_SEE_OTHER);
        }

        $this->userService->register($user);

        return $this->redirectToRoute("login", [], Response::HTTP_SEE_OTHER);
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
