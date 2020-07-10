<?php

namespace Napps\Rest\ORM;

use Napps\Rest\ORM\Drivers\DriverStrategy;
use Napps\Rest\ORM\Drivers\PgsqlPdo;
use PDO;

abstract class Model
{
    protected $driver;

    public $id;

    public function __construct()
    {
        $host = DB_SERVER;
        $dbname = DB_NAME;
        $user = DB_USERNAME;
        $pwd = DB_PASSWORD;
        $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $pwd);
        $driver = new PgsqlPdo($pdo);
        $this->setDriver($driver);
    }

    public function setDriver(DriverStrategy $driver)
    {
        $this->driver = $driver;
        $this->driver->setTable($this->table);
        return $this->driver;
    }

    protected function getDriver()
    {
        return $this->driver;
    }

    public function save()
    {
        $data = $this->getDriver()
            ->save($this)
            ->exec()
            ->one();

        if (empty($this->id)) {
            $this->id = $data['id'];
        }
    }

    public function find()
    {
        $data = $this->findOne($this->id);

        foreach ($data as $key => $value) {
            $this->{$key} = $value;
        }

        return $data;
    }

    public function findAll(array $conditions = [])
    {
        return $this->getDriver()
            ->select($conditions)
            ->exec()
            ->all();
    }

    public function findOne($id)
    {
        return $this->getDriver()
            ->select(["id" => $id])
            ->exec()
            ->one();
    }

    public function delete()
    {
        $this->getDriver()
            ->delete(["id" => $this->id])
            ->exec();
    }

    public function __get($variable)
    {
        if ($variable === "table") {
            $table = get_class($this);
            $table = explode("\\", $table);
            $table = preg_replace('/Model/', '', $table);
            return strtolower(array_pop($table));
        }
        return null;
    }
}
