<?php

namespace src\Database;

use PDO;
use PDOException;

class Database
{
    private $pdo;

    public function __construct($path)
    {
        try {
            $this->pdo = new PDO("sqlite:$path");
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Could not connect to the database: " . $e->getMessage());
        }
    }

    public function query($sql, $params = [])
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
}
