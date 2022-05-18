<?php

namespace Miniframework\App\DataStorage;

interface DataStorageInterface
{
    public function read($id);
    public function readAll();
}
