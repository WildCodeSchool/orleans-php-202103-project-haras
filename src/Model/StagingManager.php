<?php

namespace App\Model;

class StagingManager extends AbstractManager
{
    public const TABLE = 'staging';

    public function insert(array $item): void
    {
        $query = "INSERT INTO " . self::TABLE .
            "(stage_id, pupil_id)
            VALUES (:stage_id, :pupil_id)";
        $statement = $this->pdo->prepare($query);
        $statement->bindValue('stage_id', $item['stage'], \PDO::PARAM_INT);
        $statement->bindValue('pupil_id', $item['pupil_id'], \PDO::PARAM_INT);
        $statement->execute();
    }
}
