<?php

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
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->info('Create database');
        $con = new \PDO(
            'mysql:host='
            . Config::get(SystemConfig::ZED_MYSQL_HOST)
            . ';port=' . Config::get(SystemConfig::ZED_MYSQL_PORT),
            Config::get(SystemConfig::ZED_MYSQL_USERNAME),
            Config::get(SystemConfig::ZED_MYSQL_PASSWORD)
        );

        $q = 'CREATE DATABASE IF NOT EXISTS ' . Config::get(SystemConfig::ZED_MYSQL_DATABASE) . ' CHARACTER SET "utf8"';
        $con->exec($q);
    }

}
