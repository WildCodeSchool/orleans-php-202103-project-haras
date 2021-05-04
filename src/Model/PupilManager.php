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
        $statement->bindValue('parent_id', $item['parent_id'], \PDO::PARAM_INT);
        $statement->execute();
    }

    public function selectPupilsAndParents(): array
    {
        $query = 'SELECT pu.firstname AS pupil_firstname, pu.lastname AS pupil_lastname, pu.birthday, pu.experience,
        pa.* FROM ' . static::TABLE . ' pu JOIN parent pa ON pu.parent_id = pa.id ORDER BY pu.lastname ASC';
        return $this->pdo->query($query)->fetchAll();
    }

    public function selectPupilsAndParentsById(int $id): array
    {
        $query = 'SELECT pu.*, pa.firstname AS parent_firstname, pa.lastname AS parent_lastname, pa.email,
        pa.phone_number, co.id AS course FROM ' . static::TABLE . ' pu
        JOIN parent pa ON pu.parent_id = pa.id
        JOIN coursing cou ON cou.pupil_id = pu.id
        JOIN course co ON co.id = cou.course_id
        WHERE pu.id = :id';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetch();
    }

    public function update(array $pupils): void
    {
        $query = "UPDATE " . self::TABLE .
        " SET firstname=:firstname, lastname=:lastname, birthday=:birthday, experience=:experience WHERE id=:id";
        $statement = $this->pdo->prepare($query);
        $statement->bindValue('firstname', $pupils['firstname'], \PDO::PARAM_STR);
        $statement->bindValue('lastname', $pupils['lastname'], \PDO::PARAM_STR);
        $statement->bindValue('birthday', $pupils['birthday'], \PDO::PARAM_STR);
        $statement->bindValue('experience', $pupils['experience'], \PDO::PARAM_BOOL);
        $statement->bindValue('id', $pupils['id'], \PDO::PARAM_INT);
        $statement->execute();
    }
}
