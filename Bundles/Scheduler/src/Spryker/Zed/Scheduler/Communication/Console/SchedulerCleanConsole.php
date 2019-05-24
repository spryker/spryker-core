<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Scheduler\Communication\Console;

use Generated\Shared\Transfer\SchedulerRequestTransfer;
use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\Scheduler\Business\SchedulerFacadeInterface getFacade()
 */
class SchedulerCleanConsole extends Console
{
    public const COMMAND_NAME = 'scheduler:clean';
    public const DESCRIPTION = 'Cleans scheduler job(s)';

    protected const SCHEDULERS_OPTION = 'schedulers';
    protected const SCHEDULERS_OPTION_SHORTCUT = 's';
    protected const SCHEDULERS_OPTION_DESCRIPTION = 'Schedulers that will be executed on this host.';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription(self::DESCRIPTION);

        $this->addOption(
            static::SCHEDULERS_OPTION,
            static::SCHEDULERS_OPTION_SHORTCUT,
            InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
            static::SCHEDULERS_OPTION_DESCRIPTION,
            []
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
        $schedulers = $input->getOption(static::SCHEDULERS_OPTION);

        $scheduleTransfer = $this->createSchedulerTransfer($schedulers);
        $schedulerResponseTransfer = $this->getFacade()->clean($scheduleTransfer);

        $output->writeln($schedulerResponseTransfer->getMessages());

        return static::CODE_SUCCESS;
    }

    /**
     * @param array $schedulers
     *
     * @return \Generated\Shared\Transfer\SchedulerRequestTransfer
     */
    protected function createSchedulerTransfer(array $schedulers): SchedulerRequestTransfer
    {
        return (new SchedulerRequestTransfer())
            ->setSchedulers($schedulers);
    }
}
