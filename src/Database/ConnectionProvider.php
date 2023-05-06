<?php
declare(strict_types=1);

namespace App\Database;

class ConnectionProvider 
{

    public static function connectDatabase(): \PDO
    {
        $file = file_get_contents(__DIR__ . "/connectionConfig.json");
        $data = json_decode($file, true);
        $dsn = $data["dsn"];
        $user = $data["user"];
        $password = $data["password"];
        return new \PDO($dsn, $user, $password);
    }

}