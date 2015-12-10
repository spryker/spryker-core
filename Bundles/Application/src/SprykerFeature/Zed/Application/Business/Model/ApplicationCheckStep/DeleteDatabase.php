<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Application\Business\Model\ApplicationCheckStep;

use SprykerEngine\Shared\Config;
use SprykerFeature\Shared\Application\ApplicationConfig;
use Symfony\Component\Process\Process;

class DeleteDatabase extends AbstractApplicationCheckStep
{

    /**
     * @return bool
     */
    public function run()
    {
        $this->info('Delete database');

        if (Config::get(ApplicationConfig::ZED_DB_ENGINE) === 'pgsql') {
            $this->deletePostgresDatabaseIfNotExists();
        } else {
            $this->deleteMysqlDatabaseIfNotExists();
        }
    }

    /**
     * @return void
     */
    private function deletePostgresDatabaseIfNotExists()
    {
        // @todo make it work without sudo
        $dropDatabaseCommand = 'sudo dropdb --if-exists ' . Config::get(ApplicationConfig::ZED_DB_DATABASE);
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
            Config::get(ApplicationConfig::ZED_DB_ENGINE)
            . ':host='
            . Config::get(ApplicationConfig::ZED_DB_HOST)
            . ';port=' . Config::get(ApplicationConfig::ZED_DB_PORT),
            Config::get(ApplicationConfig::ZED_DB_USERNAME),
            Config::get(ApplicationConfig::ZED_DB_PASSWORD)
        );

        $q = 'DROP DATABASE IF EXISTS ' . Config::get(ApplicationConfig::ZED_DB_DATABASE);
        $con->exec($q);
    }

}
