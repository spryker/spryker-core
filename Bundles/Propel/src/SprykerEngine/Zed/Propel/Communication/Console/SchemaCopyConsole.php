<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Propel\Communication\Console;

use SprykerEngine\Zed\Propel\Business\PropelFacade;
use SprykerFeature\Zed\Console\Business\Model\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method PropelFacade getFacade()
 */
class SchemaCopyConsole extends Console
{

    const COMMAND_NAME = 'propel:schema:copy';

    protected function configure()
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription('Copy schema files from packages to generated folder');

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
        $this->getFacade()->cleanPropelSchemaDirectory();
        $this->getFacade()->copySchemaFilesToTargetDirectory();
    }

}
