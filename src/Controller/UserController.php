<?php

declare(strict_types=1);

namespace App\Controller;

use App\Database\ConnectionProvider;
use App\Database\UserTable;
use App\Model\User;
use App\Uploader\Uploader;
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
        session_start();

        $imageData = $_FILES;
        $avatar_path = null;
        if (isset($imageData["avatar"]) & $imageData["avatar"]["name"] != "") {
            $avatar_path = $this->uploader->moveAvatarToUploads($imageData["avatar"]);
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

        if (!$this->isNewUserEmail($user->getEmail())){
            return $this->redirectToRoute("registration", [], Response::HTTP_SEE_OTHER);
        }

        $_SESSION['email'] = $user->getEmail();
        $this->userTable->saveUser($user);

        return $this->redirectToRoute("show_home", [], Response::HTTP_SEE_OTHER);
    }

    public function loginUser(Request $request): Response
    {
        session_start();

        $userEmail = $request->get("email");
        $userPassword = $request->get("password");

        if (!$this->isCorrectLoginData($userEmail, $userPassword)) {
            return $this->redirectToRoute("login", [], Response::HTTP_SEE_OTHER);
        }

        $_SESSION["email"] = $userEmail;
        return $this->redirectToRoute("show_home", [], Response::HTTP_SEE_OTHER);
    }

    private function isCorrectLoginData(string $email, string $password): bool
    {
        $user = $this->userTable->findUser($email);
        return $user->getPassword() == $password;
    }

    private function isNewUserEmail(string $email): bool
    {
        return $this->userTable->findUser($email) === null;
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

    private function writeRedirectSeeOther(string $url): void
    {
        header("Location: " . $url, true, self::HTTP_STATUS_303_SEE_OTHER);
    }
}
