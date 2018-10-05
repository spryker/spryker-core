<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Setup\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\Setup\Business\SetupFacadeInterface getFacade()
 */
class JenkinsGenerateConsole extends Console
{
    public const COMMAND_NAME = 'setup:jenkins:generate';
    public const DESCRIPTION = 'Generate Jenkins jobs configuration';

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription(self::DESCRIPTION);
        $this->addOption(
            'role',
            'r',
            InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
            'Job roles to include on this host',
            []
        );

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
        $result = $this->getFacade()->generateCronjobs(
            $input->getOption('role')
        );

        $output->writeln($result);
    }
}
