<?php

declare(strict_types=1);

namespace App\Model;

class Pizza
{
    private ?int $id;
    private string $name;
    private ?string $description;
    private ?string $category;
    private float $price;
    private ?string $imgPath;

    public function __construct(?int $id, string $name, ?string $description, ?string $category, float $price, ?string $imgPath)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->category = $category;
        $this->price = $price;
        $this->imgPath = $imgPath;
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function getDescription(): ?string
    {
        return $this->description;
    }
    public function getCategory(): ?string
    {
        return $this->category;
    }
    public function getPrice(): float
    {
        return $this->price;
    }
    public function getImgPath(): ?string
    {
        return $this->imgPath;
    }
}
