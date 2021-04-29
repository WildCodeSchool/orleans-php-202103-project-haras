<?php

namespace App\Model;

class PupilManager extends AbstractManager
{
    public const TABLE = 'pupil';

    public function insert(array $item): void
    {
        $query = "INSERT INTO " . self::TABLE .
            "(firstname, lastname, birthday, experience, parent_id)
            VALUES (:firstname, :lastname, :birthday, :experience, :parent_id)";
        $statement = $this->pdo->prepare($query);
        $statement->bindValue('firstname', $item['firstname'], \PDO::PARAM_STR);
        $statement->bindValue('lastname', $item['lastname'], \PDO::PARAM_STR);
        $statement->bindValue('birthday', $item['birthday'], \PDO::PARAM_STR);
        $statement->bindValue('experience', $item['experience'], \PDO::PARAM_BOOL);
        $statement->bindvalue('parent_id', $item['parent_id'], \PDO::PARAM_INT);
        $statement->execute();
    }
}
