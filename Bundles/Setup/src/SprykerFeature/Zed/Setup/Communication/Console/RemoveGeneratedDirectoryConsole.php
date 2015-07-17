<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Setup\Communication\Console;

use SprykerFeature\Zed\Console\Business\Model\Console;
use SprykerFeature\Zed\Setup\Business\SetupFacade;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method SetupFacade getFacade()
 */
class RemoveGeneratedDirectoryConsole extends Console
{

    const COMMAND_NAME = 'setup:remove-generated-directory';
    const DESCRIPTION = 'Remove the directory where generated files are stored';

    protected function configure()
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription(self::DESCRIPTION);

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
        $this->getFacade()->removeGeneratedDirectory();
    }

}
