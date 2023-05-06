<?php

declare(strict_types=1);

namespace App\Model;

class User
{
    private string $email;
    private string $password;
    private string $name;
    private ?string $lastname;
    private ?string $address;
    private ?string $avatarPath;

    public function __construct(string $email, string $password, string $name, ?string $lastname, ?string $address, ?string $avatarPath)
    {
        $this->email = $email;
        $this->password = $password;
        $this->name = $name;
        $this->lastname = $lastname;
        $this->address = $address;
        $this->avatarPath = $avatarPath;
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
