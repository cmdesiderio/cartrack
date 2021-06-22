<?php

namespace Cartrack\Database\Model;

interface ModelInterface
{
	public function setTable(string $table): void;
	public function getTable(): string;
    public function insert(string $sql, array $params = array()): int;
    public function select(string $sql, array $params = array()): array;
    public function update(string $sql, array $params = array()): int;
    public function delete(string $sql, array $params = array()): int;
	public function getRows(): array;
	public function affectedRows(): int;
	public function lastInsertId(): int;
	public function __destruct();
}