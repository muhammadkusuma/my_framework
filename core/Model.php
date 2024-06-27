<?php

namespace Core;

use PDO;

class Model
{
    protected $conn;
    protected $table;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function all()
    {
        $query = "SELECT * FROM {$this->table}";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS, get_class($this));
    }

    public function find($id)
    {
        $query = "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetchObject(get_class($this));
    }

    public function save()
    {
        $fields = get_object_vars($this);
        $columns = implode(', ', array_keys($fields));
        $placeholders = ':' . implode(', :', array_keys($fields));
        $query = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";

        $stmt = $this->conn->prepare($query);

        foreach ($fields as $field => $value) {
            $stmt->bindValue(":{$field}", $value);
        }

        return $stmt->execute();
    }

    public function update()
    {
        $fields = get_object_vars($this);
        $columns = '';
        foreach ($fields as $field => $value) {
            $columns .= "{$field} = :{$field}, ";
        }
        $columns = rtrim($columns, ', ');
        $query = "UPDATE {$this->table} SET {$columns} WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        foreach ($fields as $field => $value) {
            $stmt->bindValue(":{$field}", $value);
        }

        return $stmt->execute();
    }

    public function delete()
    {
        $query = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);

        return $stmt->execute();
    }
}
