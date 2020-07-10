<?php

namespace Napps\Rest\Controller;

interface InterfaceController
{
    public function save();
    public function find();
    public function findOne($id);
    public function findAll(array $conditions = []);
    public function delete();
}
