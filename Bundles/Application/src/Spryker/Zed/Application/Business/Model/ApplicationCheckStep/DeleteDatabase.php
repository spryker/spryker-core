<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Application\Business\Model\ApplicationCheckStep;

use Spryker\Shared\Config;
use Spryker\Shared\Application\ApplicationConstants;
use Symfony\Component\Process\Process;

class DeleteDatabase extends AbstractApplicationCheckStep
{

    /**
     * @return bool
     */
    public function run()
    {
        $this->info('Delete database');

        if (Config::get(ApplicationConstants::ZED_DB_ENGINE) === 'pgsql') {
            $this->deletePostgresDatabaseIfNotExists();
        } else {
            $this->deleteMysqlDatabaseIfNotExists();
        }
    }

    private function closePostgresConnections()
    {
        $dropDatabaseCommand = "PGPASSWORD='easy' psql -U postgres -w  -c 'SELECT pg_terminate_backend(pg_stat_activity.pid) FROM pg_stat_activity WHERE pid <> pg_backend_pid()'";
        $process = new Process($dropDatabaseCommand);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new \RuntimeException($process->getErrorOutput());
        }
    }

    /**
     * @return void
     */
    private function deletePostgresDatabaseIfNotExists()
    {
        $this->closePostgresConnections();

        // @todo make it work without sudo
        $dropDatabaseCommand = 'sudo dropdb --if-exists ' . Config::get(ApplicationConstants::ZED_DB_DATABASE);
        $process = new Process($dropDatabaseCommand);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new \RuntimeException($process->getErrorOutput());
        }
    }

    /**
     * @return void
     */
    private function deleteMysqlDatabaseIfNotExists()
    {
        $con = new \PDO(
            Config::get(ApplicationConstants::ZED_DB_ENGINE)
            . ':host='
            . Config::get(ApplicationConstants::ZED_DB_HOST)
            . ';port=' . Config::get(ApplicationConstants::ZED_DB_PORT),
            Config::get(ApplicationConstants::ZED_DB_USERNAME),
            Config::get(ApplicationConstants::ZED_DB_PASSWORD)
        );

        $q = 'DROP DATABASE IF EXISTS ' . Config::get(ApplicationConstants::ZED_DB_DATABASE);
        $con->exec($q);
    }

}
