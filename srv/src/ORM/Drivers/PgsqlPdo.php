<?php

namespace Napps\Rest\ORM\Drivers;

use Napps\Rest\ORM\Model;

class PgsqlPdo implements DriverStrategy
{
    protected $pdo;
    protected $stm;
    protected $table;
    protected $colsNotBind = ["id"];

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function setTable(string $table)
    {
        $this->table = $table;
    }

    public function save(Model $data)
    {
        if (!empty($data->id)) {
            $this->update($data);
        } else {
            $this->insert($data);
        }

        return $this;
    }

    public function insert(Model $data)
    {
        $query = 'INSERT INTO %s (%s) VALUES (%s) RETURNING *';
        $this->prepareSql($query, $data)->bindParams($data);

        return $this;
    }

    public function update(Model $data)
    {
        if (empty($data->id)) {
            throw new \Exception("Id is required");
        }
        $query = 'UPDATE %s SET (%s) = (%s) WHERE id=:id RETURNING *';
        $this->prepareSql($query, $data); //print_r($data); exit; //echo $query; exit;
        $this->bindParams($data);
        $this->stm->bindValue(':id', $data->id);

        return $this;
    }

    public function select(array $conditions = [])
    {
        $this->queryInDB('SELECT * FROM ' . $this->table, $conditions);

        return $this;
    }

    public function delete(array $conditions = [])
    {
        $this->queryInDB('DELETE FROM ' . $this->table, $conditions);

        return $this;
    }

    public function queryInDB($query, array $conditions = [])
    {
        $colsNotBind = $this->colsNotBind;
        $this->colsNotBind = [];
        //$query = 'SELECT * FROM ' . $this->table;

        if ($conditions) {//print_r($conditions); exit;
            $query .= ' WHERE ' . $this->prepareWhereSql($conditions);
        }
        $this->prepareSql($query, $conditions)->bindParams($conditions);
        //$this->prepareSql($query, $conditions)->bindParams([]);
        $this->colsNotBind = $colsNotBind;

        return $this;
    }

    public function exec(string $query = null)
    {
        if ($query) {
            $this->stm = $this->pdo->prepare($query);
        }

        try {
            $this->stm->execute();
        } catch (\PDOException $exception) {
            echo $exception->getMessage();
        }
        return $this;
    }

    public function one()
    {
        return $this->stm->fetch(\PDO::FETCH_ASSOC);
    }

    public function all()
    {
        return $this->stm->fetchaLL(\PDO::FETCH_ASSOC);
    }

    protected function prepareSql($query, $data)
    {
        $fields = [];
        $fieldsToBind = [];

        foreach ($data as $field => $value) {
            if (!in_array($field, $this->colsNotBind)) {
                $fields[] = $field;
                $fieldsToBind[] = ':' . $field;
            }
        }

        $fields = implode(',', $fields);
        $fieldsToBind = implode(',', $fieldsToBind);
        $query = sprintf($query, $this->table, $fields, $fieldsToBind);

        $this->stm = $this->pdo->prepare($query);

        return $this;
    }

    protected function bindParams($data)
    {
        foreach ($data as $field => $value) {
            if (!in_array($field, $this->colsNotBind)) {
                $this->stm->bindValue(':' . $field, $value);
            }
        }
    }

    protected function prepareWhereSql($conditions)
    {
        $fields = [];

        foreach ($conditions as $field => $value) {
            if (!in_array($field, $this->colsNotBind)) {
                $fields[] = $field . '=:' . $field;
            }
        }

        return implode(' and ', $fields);
    }
}
