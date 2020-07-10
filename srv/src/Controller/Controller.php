<?php

namespace Napps\Rest\Controller;

use Napps\Rest\ORM\Model;

class Controller implements InterfaceController
{
    public Model $model;

    public function __construct(array $params = [])
    {
        $ignore = ["functionUrl"];
        if (!empty($this->model)) {
            foreach ($params as $key => $value) {
                if (!in_array($key, $ignore)) {
                    $this->model->{$key} = $value;
                }
            }
        }
    }

    public function save()
    {
        $this->model->save();
    }

    public function find()
    {
        return $this->model->find();
    }

    public function findOne($id)
    {
        return $this->model->findOne($id);
    }

    public function findAll(array $conditions = [])
    {
        array_shift($conditions);
        return $this->model->findAll($conditions);
    }

    public function delete()
    {
        $this->model->delete();
    }
}
