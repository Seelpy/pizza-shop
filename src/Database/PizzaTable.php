<?php

declare(strict_types=1);

namespace App\Database;

use App\Model\Pizza;


class PizzaTable
{
    private \PDO $connection;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    
    /**
     * getAllPizzas
     *
     * @return Pizza[]
     */
    public function getAllPizzas(): array
    {
        $query = <<< SQL
            SELECT
                id, name, description, category, price, img_path
            FROM pizza
        SQL;

        $statement = $this->connection->query($query);
        $pizzas = [];
        foreach ($statement->fetchAll(\PDO::FETCH_ASSOC) as $row) {
            $pizzas[] = $this->createPizzaFromRow($row);
        }

        return $pizzas;
    }

    public function findPizza(int $id): ?Pizza
    {
        $query = <<< SQL
            SELECT
                id, name, description, category, price, img_path
            FROM pizza
            WHERE id = $$id
        SQL;

        $statement = $this->connection->query($query);
        if ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            return $this->createPizzaFromRow($row);
        }

        return null;
    }

    function savePizza(Pizza $pizza): int
    {
        $query = <<< SQL
            INSERT INTO user (id, name, description, category, price, img_path)
            VALUES (:id, :name, :description, :category, :price, :img_path)
        SQL;

        $statement = $this->connection->prepare($query);
        $statement->execute([
            ":id" => $pizza->getId(),
            ":name" => $pizza->getName(),
            ":description" => $pizza->getDescription(),
            ":category" => $pizza->getCategory(),
            ":price" => $pizza->getPrice(),
            ":img_path" => $pizza->getImgPath(),
        ]);

        return (int)$this->connection->lastInsertId();
    }

    private function createPizzaFromRow(array $row): Pizza
    {
        return new Pizza(
            id: (int)$row["id"],
            name: $row["name"],
            description: $row["description"],
            category: $row["category"],
            price: (float)$row["price"],
            imgPath: $row["img_path"],
        );
    }
}
