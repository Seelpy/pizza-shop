<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Pizza;
use App\Repository\PizzaRepository;
use App\Service\Data\PizzaData;

class PizzaService implements PizzaServiceInterface
{
    private PizzaRepository $pizzaRepository;

    public function __construct(PizzaRepository $pizzaRepository)
    {
        $this->pizzaRepository = $pizzaRepository;
    }

    public function findById(int $id): ?PizzaData
    {
        $pizza = $this->pizzaRepository->findById($id);

        if ($pizza === null) {
            throw new \InvalidArgumentException("Пицца с id($id) отсутсвует");
        }

        return new PizzaData(
            id: $pizza->getId(),
            name: $pizza->getName(),
            description: $pizza->getDescription(),
            category: $pizza->getCategory(),
            price: $pizza->getPrice(),
            imgPath: $pizza->getImgPath(),
        );
    }

    public function create(string $name, string $description, string $category, int $price, string $imagePath): void
    {
        $this->pizzaRepository->store(new Pizza(
            id: null,
            name: $name,
            description: $description,
            category: $category,
            price: $price,
            imgPath: $imagePath,
        ));
    }

    public function deletePizza(int $id): void
    {
        $pizza = $this->pizzaRepository->findById($id);
        $this->pizzaRepository->delete($pizza);
    }

    /**
     * @return PizzaData[]
     */
    public function listPizza(): array
    {
        return $this->pizzaRepository->listAll();
    }
}
