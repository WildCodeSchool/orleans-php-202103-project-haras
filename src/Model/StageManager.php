<?php

namespace App\Model;

class StageManager extends AbstractManager
{
    public const TABLE = 'stage';

    public function insert(array $item): void
    {
        $query = "INSERT INTO " . self::TABLE .
        "(name, starting_day, ending_day, capacity, age)
        VALUES (:name, :starting_day, :ending_day, :capacity, :age)";
        $statement = $this->pdo->prepare($query);
        $statement->bindValue('name', $item['name'], \PDO::PARAM_STR);
        $statement->bindValue('starting_day', $item['starting_day'], \PDO::PARAM_STR);
        $statement->bindValue('ending_day', $item['ending_day'], \PDO::PARAM_STR);
        $statement->bindValue('capacity', $item['capacity'], \PDO::PARAM_INT);
        $statement->bindValue('age', $item['age'], \PDO::PARAM_INT);
        $statement->execute();
    }

    public function update(array $stage): void
    {
        $query = "UPDATE " . self::TABLE .
        " SET name=:name, starting_day=:starting_day, ending_day=:ending_day,
        capacity=:capacity, age=:age WHERE id=:id";
        $statement = $this->pdo->prepare($query);
        $statement->bindValue('name', $stage['name'], \PDO::PARAM_STR);
        $statement->bindValue('starting_day', $stage['starting_day'], \PDO::PARAM_STR);
        $statement->bindValue('ending_day', $stage['ending_day'], \PDO::PARAM_STR);
        $statement->bindValue('capacity', $stage['capacity'], \PDO::PARAM_INT);
        $statement->bindValue('age', $stage['age'], \PDO::PARAM_INT);
        $statement->bindValue('id', $stage['id'], \PDO::PARAM_INT);
        $statement->execute();
    }
}
