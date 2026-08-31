<?php
declare(strict_types=1);

namespace App\Base\Db;

use App\Exception\AppRuntimeException;
use PDO;
use PDOStatement;

class DbConnection implements IDbConnection
{
    private ?PDO $connection;

    public function __construct(array $config)
    {
        $requiredParameters = ['host', 'db', 'charset', 'user', 'pass'];

        foreach ($requiredParameters as $parameter) {
            if (!array_key_exists($parameter, $config)) {
                throw new AppRuntimeException(sprintf(
                    'Database configuration parameter "%s" is required.',
                    $parameter
                ));
            }
        }

        $dsn = sprintf(
            'mysql:host=%s;dbname=%s;charset=%s',
            $config['host'],
            $config['db'],
            $config['charset']
        );

        $this->connection = new PDO(
            $dsn,
            $config['user'],
            $config['pass'],
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        );
    }

    public function select(string $sql, array $parameters = []): array
    {
        return $this->execute($sql, $parameters)->fetchAll();
    }

    public function insert(string $sql, array $parameters = []): int
    {
        $this->execute($sql, $parameters);

        $id = $this->connection->lastInsertId();

        if ($id === false) {
            throw new AppRuntimeException('Failed insert data');
        }

        return (int) $id;
    }

    public function update(string $sql, array $parameters = []): int
    {
        return $this->execute($sql, $parameters)->rowCount();
    }

    public function delete(string $sql, array $parameters = []): int
    {
        return $this->execute($sql, $parameters)->rowCount();
    }

    public function execute(string $sql, array $parameters = []): PDOStatement
    {
        $statement = $this->connection->prepare($sql);
        $statement->execute($parameters);

        return $statement;
    }
}
