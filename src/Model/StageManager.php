<?php

namespace App\Model;

Class StageManager extends AbstractManager
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
}