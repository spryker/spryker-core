<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Communication\Console;

use Spryker\Shared\Config\Config;
use Spryker\Shared\Propel\PropelConstants;
use Spryker\Zed\Console\Business\Model\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class CreateDatabaseConsole extends Console
{

    const COMMAND_NAME = 'propel:database:create';

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription('Create database if it does not already exist');

        parent::configure();
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->info('Creating Database');

        if (Config::get(PropelConstants::ZED_DB_ENGINE) === Config::get(PropelConstants::ZED_DB_ENGINE_PGSQL)) {
            $this->createPostgresDatabaseIfNotExists();
        } else {
            $this->createMysqlDatabaseIfNotExists();
        }
    }

    /**
     * @throws \Exception
     *
     * @todo no sudo, vagrant user is missing for pgsql
     *
     * @return void
     */
    protected function createPostgresDatabaseIfNotExists()
    {
        $databaseExists = $this->existsPostgresDatabase();
        if (!$databaseExists) {
            $createDatabaseCommand = sprintf(
                'psql -U %s -w  -c "CREATE DATABASE \"%s\" WITH ENCODING=\'UTF8\' LC_COLLATE=\'en_US.UTF-8\' LC_CTYPE=\'en_US.UTF-8\' CONNECTION LIMIT=-1 TEMPLATE=\"template0\"; "',
                'postgres',
                Config::get(PropelConstants::ZED_DB_DATABASE)
            );

            $process = new Process($createDatabaseCommand);
            $process->run();

            if (!$process->isSuccessful()) {
                throw new \RuntimeException($process->getErrorOutput());
            }
        }
    }

    /**
     * @throws \Exception
     *
     * @return bool
     *
     * @todo no sudo, vagrant user is missing for pgsql
     */
    protected function existsPostgresDatabase()
    {
        $databaseExistsCommand = sprintf(
            'psql -U %s -lqt | cut -d \| -f 1 | grep -w %s | wc -l',
            'postgres',
            Config::get(PropelConstants::ZED_DB_DATABASE)
        );

        $process = new Process($databaseExistsCommand);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new \RuntimeException($process->getErrorOutput());
        }

        $returnValue = (int) $process->getOutput();
        return (bool) $returnValue;
    }

    /**
     * @throws \Exception
     *
     * @return void
     */
    private function createMysqlDatabaseIfNotExists()
    {
        $connection = new \PDO(
            Config::get(PropelConstants::ZED_DB_ENGINE)
            . ':host='
            . Config::get(PropelConstants::ZED_DB_HOST)
            . ';port=' . Config::get(PropelConstants::ZED_DB_PORT),
            Config::get(PropelConstants::ZED_DB_USERNAME),
            Config::get(PropelConstants::ZED_DB_PASSWORD)
        );

        $query = 'CREATE DATABASE IF NOT EXISTS ' . Config::get(PropelConstants::ZED_DB_DATABASE) . ' CHARACTER SET "utf8"';
        $connection->exec($query);
    }

}
