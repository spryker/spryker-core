<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Propel\Communication\Console;

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
        if(Config::get(SystemConfig::ZED_DB_ENGINE) == 'pgsql') {
            $this->info('!! Postgresql database has to be created beforehand');
            return;
        }


        $con = new \PDO(
            Config::get(SystemConfig::ZED_DB_ENGINE)
            . ':host='
            . Config::get(SystemConfig::ZED_DB_HOST)
            . ';port=' . Config::get(SystemConfig::ZED_DB_PORT),
            Config::get(SystemConfig::ZED_DB_USERNAME),
            Config::get(SystemConfig::ZED_DB_PASSWORD)
        );
        $q = 'CREATE DATABASE IF NOT EXISTS ' . Config::get(SystemConfig::ZED_DB_DATABASE) . ' CHARACTER SET "utf8"';
        $con->exec($q);
    }

}
