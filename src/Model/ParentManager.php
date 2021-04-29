<?php

namespace App\Model;

class ParentManager extends AbstractManager
{
    public const TABLE = 'parent';

    public function insert(array $item): int
    {
        $query = "INSERT INTO " . self::TABLE .
            "(firstname, lastname, email, phone_number)
            VALUES (:firstname, :lastname, :email, :phone_number)";
        $statement = $this->pdo->prepare($query);
        $statement->bindValue('firstname', $item['parentfirstname'], \PDO::PARAM_STR);
        $statement->bindValue('lastname', $item['parentlastname'], \PDO::PARAM_STR);
        $statement->bindValue('email', $item['email'], \PDO::PARAM_STR);
        $statement->bindValue('phone_number', $item['phone'], \PDO::PARAM_STR);
        $statement->execute();
        return (int)$this->pdo->lastInsertId();
    }
}
