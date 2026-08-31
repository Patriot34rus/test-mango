<?php
declare(strict_types=1);

namespace App\Base\Db;

use PDOStatement;

interface IDbConnection
{
    public function select(string $sql, array $parameters = []): array;

    public function insert(string $sql, array $parameters = []): int;

    public function update(string $sql, array $parameters = []): int;

    public function delete(string $sql, array $parameters = []): int;

    public function execute(string $sql, array $parameters = []): PDOStatement;

}
