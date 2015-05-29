<?php

namespace SprykerFeature\Zed\Setup\Communication\Console\Propel;

use SprykerFeature\Shared\System\SystemConfig;
use SprykerFeature\Zed\Console\Business\Model\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class SchemaCopyConsole extends Console
{

    const COMMAND_NAME = 'setup:propel:schema:copy';

    protected function configure()
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription('Copies schema file from packages to generated folder');

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

    /**
     * @return \SprykerFeature\Zed\Setup\Business\SetupFacade
     */
    private function getFacade()
    {
        return $this->getLocator()->setup()->facade();
    }
}
