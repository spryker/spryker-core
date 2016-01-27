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

        if (Config::get(ApplicationConstants::ZED_DB_ENGINE) === ApplicationConstants::ZED_DB_ENGINE_PGSQL) {
            $this->deletePostgresDatabaseIfNotExists();
        } else {
            $this->deleteMysqlDatabaseIfNotExists();
        }
    }

    /**
     * @throws \RuntimeException
     *
     * @return void
     */
    protected function closePostgresConnections()
    {
        $dropDatabaseCommand = sprintf(
            'psql -U %s -w  -c "SELECT pg_terminate_backend(pg_stat_activity.pid) FROM pg_stat_activity WHERE pid <> pg_backend_pid() AND pg_stat_activity.datname = \'%s\';"',
            'postgres',
            Config::get(ApplicationConstants::ZED_DB_DATABASE)
        );

        $process = new Process($dropDatabaseCommand);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new \RuntimeException($process->getErrorOutput());
        }
    }

    /**
     * @throws \RuntimeException
     *
     * @return void
     */
    protected function deletePostgresDatabaseIfNotExists()
    {
        $this->closePostgresConnections();

        $dropDatabaseCommand = sprintf(
            'psql -U %s -w  -c "DROP DATABASE IF EXISTS \"%s\";"',
            'postgres',
            Config::get(ApplicationConstants::ZED_DB_DATABASE)
        );

        $process = new Process($dropDatabaseCommand);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new \RuntimeException($process->getErrorOutput());
        }
    }

    /**
     * @return void
     */
    protected function deleteMysqlDatabaseIfNotExists()
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
