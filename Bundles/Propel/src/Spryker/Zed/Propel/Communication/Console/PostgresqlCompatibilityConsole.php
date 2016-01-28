<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Propel\Communication\Console;

use Spryker\Shared\Config;
use Spryker\Shared\Propel\PropelConstants;
use Spryker\Zed\Console\Business\Model\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\Propel\Business\PropelFacade getFacade()
 */
class PostgresqlCompatibilityConsole extends Console
{

    const COMMAND_NAME = 'propel:pg-sql-compat';

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription('Adjust Propel-XML schema files to work with PostgreSQL');

        parent::configure();
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (Config::get(PropelConstants::ZED_DB_ENGINE) === PropelConstants::ZED_DB_ENGINE_PGSQL) {
            $this->info('Adjust propel config for PostgreSQL and missing functions (group_concat)');
            $this->getFacade()->adjustPropelSchemaFilesForPostgresql();
            $this->getFacade()->adjustPostgresqlFunctions();
        }
    }

}
