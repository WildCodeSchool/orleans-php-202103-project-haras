<?php

namespace App\Model;

class CourseManager extends AbstractManager
{
    public const TABLE = 'course';

    /**
     * Insert new item in database
     */
    public function insert(array $item): void
    {
        $query = "INSERT INTO " . self::TABLE .
            "(name, day, time, duration, capacity)
            VALUES (:name, :day, :time, :duration, :capacity)";
        $statement = $this->pdo->prepare($query);
        $statement->bindValue('name', $item['name'], \PDO::PARAM_STR);
        $statement->bindValue('day', $item['day'], \PDO::PARAM_STR);
        $statement->bindValue('time', $item['time'], \PDO::PARAM_STR);
        $statement->bindValue('duration', $item['duration'], \PDO::PARAM_INT);
        $statement->bindValue('capacity', $item['capacity'], \PDO::PARAM_INT);
        $statement->execute();
    }
}
