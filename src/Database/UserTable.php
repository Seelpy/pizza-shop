<?php

declare(strict_types=1);

namespace App\Database;

use App\Model\User;

class UserTable
{
    private \PDO $connection;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    public function findUser(string $email): ?User
    {
        $query = <<< SQL
            SELECT
                email, password, name, lastname, address, avatar_path
            FROM user
            WHERE email = '$email'
        SQL;

        $statement = $this->connection->query($query);
        if ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            return $this->createUserFromRow($row);
        }

        return null;
    }

    function saveUser(User $user)
    {
        $query = <<< SQL
            INSERT INTO user (email, password, name, lastname, address, avatar_path)
            VALUES (:email, :password, :name, :lastname, :address, :avatar_path)
        SQL;

        $statement = $this->connection->prepare($query);

        $statement->execute([
            ":email" => $user->getEmail(),
            ":password" => $user->getPassword(),
            ":name" => $user->getName(),
            ":lastname" => $user->getLastName(),
            ":address" => $user->getAddress(),
            ":avatar_path" => $user->getAvatarPath(),
        ]);
    }

    private function createUserFromRow(array $row): User
    {
        return new User(
            id: $row["id"],
            email: $row["email"],
            password: $row["password"],
            name: $row["name"],
            lastname: $row["lastname"],
            address: $row["address"],
            avatarPath: $row["avatar_path"],
        );
    }
}
