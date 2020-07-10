<?php

namespace Napps\Rest\ORM\Drivers;

use Napps\Rest\ORM\Model;

interface DriverStrategy
{
    public function save(Model $data);
    public function insert(Model $data);
    public function update(Model $data);
    public function select(array $conditions = []);
    public function delete(array $data = []);
    public function exec(string $query = null);
    public function one();
    public function all();
}
