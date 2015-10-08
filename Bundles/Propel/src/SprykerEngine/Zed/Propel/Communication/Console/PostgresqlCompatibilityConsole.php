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
use SprykerEngine\Zed\Propel\Business\PropelFacade;

/**
 * @method PropelFacade getFacade()
 */
class PostgresqlCompatibilityConsole extends Console
{

    const COMMAND_NAME = 'setup:propel:pg-sql-compat';

    protected function configure()
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription('Adjust Propel-XML schema files to work with PostgreSQL');

        parent::configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (Config::get(SystemConfig::ZED_DB_ENGINE) === 'pgsql') {
            $this->info('Adjust propel config for PostgreSQL and missing functions (group_concat)');
            $this->getFacade()->adjustPropelSchemaFilesForPostgresql();
            $this->getFacade()->adjustPostgresqlFunctions();
        }
    }

}
