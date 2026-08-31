<?php
declare(strict_types=1);

namespace App\Command;

use App\Base\AppFactory;
use RuntimeException;

class MigrateRun
{
    public function __construct(private readonly string $migrationPath){

    }

    public function run(): void
    {
        $factory = AppFactory::createByDefault();
        $connection = $factory->createDbConnection();
        $sql = file_get_contents($this->migrationPath);

        if ($sql === false) {
            throw new RuntimeException(sprintf(
                'Unable to read migration file "%s".',
                $this->migrationPath
            ));
        }

        $statements = preg_split('/;\s*(?:\R|$)/', $sql, flags: PREG_SPLIT_NO_EMPTY);

        if ($statements === false) {
            throw new RuntimeException('Unable to parse the migration file.');
        }

        foreach ($statements as $statement) {
            $connection->execute(trim($statement));
        }
    }
}
require __DIR__ . '/vendor/autoload.php';
$migrationPath = getenv('ROOT_DIR').'/migration/migration.sql';

new MigrateRun($migrationPath)->run();
