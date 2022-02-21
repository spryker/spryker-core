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
 * @deprecated Use {@link \Spryker\Zed\Scheduler\Communication\Console\SchedulerSetupConsole} instead.
 *
 * @method \Spryker\Zed\Setup\Business\SetupFacadeInterface getFacade()
 * @method \Spryker\Zed\Setup\Communication\SetupCommunicationFactory getFactory()
 */
class JenkinsGenerateConsole extends Console
{
    /**
     * @var string
     */
    public const COMMAND_NAME = 'setup:jenkins:generate';

    /**
     * @var string
     */
    public const DESCRIPTION = 'Generate Jenkins jobs configuration';

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName(static::COMMAND_NAME);
        $this->setDescription(static::DESCRIPTION);
        $this->addOption(
            'role',
            'r',
            InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
            'Job roles to include on this host',
            [],
        );

        parent::configure();
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var array $roles */
        $roles = $input->getOption('role');
        $result = $this->getFacade()->generateCronjobs($roles);

        $output->writeln($result);

        return static::CODE_SUCCESS;
    }
}
