<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Propel\Communication\Console;

use Spryker\Zed\Console\Business\Model\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\Propel\Business\PropelFacade getFacade()
 */
class SchemaCopyConsole extends Console
{

    const COMMAND_NAME = 'propel:schema:copy';

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription('Copy schema files from packages to generated folder');

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
        $this->info('Clean schema directory');
        $this->getFacade()->cleanPropelSchemaDirectory();
        $this->info('Copy and merge schema files');
        $this->getFacade()->copySchemaFilesToTargetDirectory();
    }

}
