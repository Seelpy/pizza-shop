<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\Data\UserData;
use App\Service\UserServiceInterface;

class UserService implements UserServiceInterface
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(User $user): void
    {
        $existingUser = $this->userRepository->findByEmail($user->getEmail());
        if ($existingUser !== null) {
            throw new \InvalidArgumentException("Почта(" . $user->getEmail() . ") уже зарегестрирована");
        }

        $this->userRepository->store($user);
    }

    public function isCorrectLoginData(string $email, string $password): bool
    {
        $user = $this->userRepository->findByEmail($email);
        return $user->getPassword() == $password;
    }

    public function findUserByEmail(string $email): UserData
    {
        $user = $this->userRepository->findByEmail($email);
        if ($user === null) {
            throw new \InvalidArgumentException("Пользователь не найден");
        }

        return new UserData(
            id: $user->getId(),
            email: $user->getEmail(),
            password: $user->getPassword(),
            name: $user->getName(),
            lastname: $user->getLastName(),
            address: $user->getAddress(),
            avatarPath: $user->getAvatarPath(),
        );
    }
}
