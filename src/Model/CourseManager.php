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
            "(name, day, time, duration, capacity, age)
            VALUES (:name, :day, :time, :duration, :capacity, :age)";
        $statement = $this->pdo->prepare($query);
        $statement->bindValue('name', $item['name'], \PDO::PARAM_STR);
        $statement->bindValue('day', $item['day'], \PDO::PARAM_INT);
        $statement->bindValue('time', $item['time'], \PDO::PARAM_STR);
        $statement->bindValue('duration', $item['duration'], \PDO::PARAM_INT);
        $statement->bindValue('capacity', $item['capacity'], \PDO::PARAM_INT);
        $statement->bindValue('age', $item['age'], \PDO::PARAM_INT);
        $statement->execute();
    }

    public function update(array $item): void
    {
        $query = "UPDATE " . self::TABLE .
            " SET name=:name, day=:day, time=:time, duration=:duration, capacity=:capacity, age=:age WHERE id=:id";
        $statement = $this->pdo->prepare($query);
        $statement->bindValue('name', $item['name'], \PDO::PARAM_STR);
        $statement->bindValue('day', $item['day'], \PDO::PARAM_INT);
        $statement->bindValue('time', $item['time'], \PDO::PARAM_STR);
        $statement->bindValue('duration', $item['duration'], \PDO::PARAM_INT);
        $statement->bindValue('capacity', $item['capacity'], \PDO::PARAM_INT);
        $statement->bindValue('age', $item['age'], \PDO::PARAM_INT);
        $statement->bindValue('id', $item['id'], \PDO::PARAM_INT);
        $statement->execute();
    }

    public function selectDistinctName(): array
    {
        $query = 'SELECT DISTINCT name, MIN(age) as age,
                AVG(duration) as duration FROM ' . static::TABLE . ' GROUP BY name';
        return $this->pdo->query($query)->fetchAll();
    }
}
