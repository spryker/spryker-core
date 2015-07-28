<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Propel\Communication\Console;

use Propel\Runtime\Connection\ConnectionManagerSingle;
use Propel\Runtime\Propel;
use Propel\Runtime\ServiceContainer\StandardServiceContainer;
use SprykerEngine\Shared\Config;
use SprykerFeature\Shared\System\SystemConfig;
use SprykerFeature\Zed\Console\Business\Model\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class CreateDatabaseConsole extends Console
{

    const COMMAND_NAME = 'setup:propel:database:create';

    protected function configure()
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription('Create database if it does not already exist');

        parent::configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->info('Creating Database');

        if(Config::get(SystemConfig::ZED_DB_ENGINE) === 'pgsql') {
            $this->createPostgresDatabaseIfNotExists();
        } else {
            $this->createMysqlDatabaseIfNotExists();
        }
    }

    /**
     * @throws \Exception
     * @todo no sudo, vagrant user is missing for pgsql
     */
    private function createPostgresDatabaseIfNotExists()
    {
        $databaseExists = $this->existsPostgresDatabase();
        if (!$databaseExists) {
            $createDatabaseCommand = 'sudo createdb '  . Config::get(SystemConfig::ZED_DB_DATABASE) . ' -E UTF8 -T template0';
            $process = new Process($createDatabaseCommand);
            $process->run();

            if (!$process->isSuccessful()) {
                throw new \RuntimeException($process->getErrorOutput());
            }
        }
    }

    /**
     * @throws \Exception
     * @return bool
     * @todo no sudo, vagrant user is missing for pgsql
     */
    private function existsPostgresDatabase()
    {
        $databaseExistsCommand = 'echo -n "$(sudo psql -lqt | cut -d \| -f 1 | grep -w ' . Config::get(SystemConfig::ZED_DB_DATABASE) . ' | wc -l)"';
        $process = new Process($databaseExistsCommand);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new \RuntimeException($process->getErrorOutput());
        }

        return $process->getOutput();
    }

    private function createMysqlDatabaseIfNotExists()
    {
        $connection = new \PDO(
            Config::get(SystemConfig::ZED_DB_ENGINE)
            . ':host='
            . Config::get(SystemConfig::ZED_DB_HOST)
            . ';port=' . Config::get(SystemConfig::ZED_DB_PORT),
            Config::get(SystemConfig::ZED_DB_USERNAME),
            Config::get(SystemConfig::ZED_DB_PASSWORD)
        );

        $query = 'CREATE DATABASE IF NOT EXISTS ' . Config::get(SystemConfig::ZED_DB_DATABASE) . ' CHARACTER SET "utf8"';
        $connection->exec($query);
    }

}
