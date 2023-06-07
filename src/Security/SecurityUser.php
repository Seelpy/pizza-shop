<?php
declare(strict_types=1);

namespace App\Security;

use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\UserRole;

class SecurityUser implements UserInterface, PasswordAuthenticatedUserInterface
{
    private int $id;
    private string $email;
    private string $password;
    private int $role;

    public function __construct(int $id, string $email, string $password, int $role)
    {
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }
    
    public function getPassword(): string
    {
        return $this->password;
    }

    public function getRoles()
    {
        if ($this->role === UserRole::ADMIN)
        {
            return ['ROLE_ADMIN', 'ROLE_USER'];
        }
        return ['ROLE_USER'];
    }

    public function getUsername()
    {
        return $this->email;
    }

    public function eraseCredentials()
    {}

    public function getSalt()
    {
        return null;
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRole::ADMIN;
    }

    public function getId(): int
    {
        return $this->id;
    }
}