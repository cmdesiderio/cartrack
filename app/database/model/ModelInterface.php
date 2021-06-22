<?php

namespace Cartrack\Database\Model;

interface ModelInterface
{
    public function insert(string $sql, array $params = array()): int;  //return last id
    public function select(string $sql, array $params = array()): array;//return rows
    public function update(string $sql, array $params = array()): int;  //return affectedrows count
    public function delete(string $sql, array $params = array()): int;  //return affectedrows count
}