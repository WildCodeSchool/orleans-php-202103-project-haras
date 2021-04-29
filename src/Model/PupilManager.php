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

    public function selectPupilsAndParents(): array
    {
        $query = 'SELECT pu.firstname AS pupil_firstname, pu.lastname AS pupil_lastname, pu.birthday, pu.experience,
        pa.* FROM ' . static::TABLE . ' pu JOIN parent pa ON pu.parent_id = pa.id ORDER BY pu.lastname ASC';
        return $this->pdo->query($query)->fetchAll();
    }
}
