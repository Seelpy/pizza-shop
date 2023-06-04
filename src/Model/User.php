<?php

declare(strict_types=1);

namespace App\Model;

class User
{
    public function __construct(
        private ?int $id,
        private string $email,
        private string $password,
        private string $name,
        private ?string $lastname,
        private ?string $address,
        private ?string $avatarPath)
    {}

    public function getId(): int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
    
    public function getPassword(): string
    {
        return $this->password;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLastName(): ?string
    {
        return $this->lastname;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function getAvatarPath(): ?string
    {
        return $this->avatarPath;
    }
}