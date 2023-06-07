<?php

declare(strict_types=1);

namespace App\Service;

use App\Service\Data\PizzaData;

interface PizzaServiceInterface
{
    public function findById(int $id): ?PizzaData;

    public function create(string $name, string $description, string $category, int $price, string $imagePath): void;

    public function deletePizza(int $id): void;

    /**
     * @return PizzaData[]
     */
    public function listPizza(): array;
}
