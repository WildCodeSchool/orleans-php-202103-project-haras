<?php

namespace App\Model;

class CoursingManager extends AbstractManager
{
    public const TABLE = 'coursing';

    public function insert(array $item): void
    {
        $query = "INSERT INTO " . self::TABLE .
            "(course_id, pupil_id)
            VALUES (:course_id, :pupil_id)";
        $statement = $this->pdo->prepare($query);
        $statement->bindValue('course_id', $item['course'], \PDO::PARAM_INT);
        $statement->bindValue('pupil_id', $item['pupil_id'], \PDO::PARAM_INT);
        $statement->execute();
    }
}
