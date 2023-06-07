<?php

declare(strict_types=1);

namespace App\Service;

use App\Service\Data\UserData;
use App\Entity\User;

interface UserServiceInterface
{
    public function register(User $user): void;

    public function isCorrectLoginData(string $email, string $password): bool;

    public function findUserByEmail(string $email): UserData;
}
