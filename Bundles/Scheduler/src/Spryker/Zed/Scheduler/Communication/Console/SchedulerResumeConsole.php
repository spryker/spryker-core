<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Scheduler\Communication\Console;

use Generated\Shared\Transfer\SchedulerTransfer;
use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\Scheduler\Business\SchedulerFacadeInterface getFacade()
 */
class SchedulerResumeConsole extends Console
{
    public const COMMAND_NAME = 'scheduler:resume';
    public const DESCRIPTION = 'Resumes scheduler job(s)';

    protected const SCHEDULERS_OPTION = 'schedulers';
    protected const SCHEDULERS_OPTION_SHORTCUT = 's';
    protected const SCHEDULERS_OPTION_DESCRIPTION = 'Schedulers that will be executed on this host.';

    protected const JOBS_OPTION = 'jobs';
    protected const JOBS_OPTION_SHORTCUT = 'j';
    protected const JOBS_OPTION_DESCRIPTION = 'Scheduler job(s) that will be enabled.';

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

        $this->addOption(
            static::JOBS_OPTION,
            static::JOBS_OPTION_SHORTCUT,
            InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
            static::JOBS_OPTION_DESCRIPTION,
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
        $jobs = $input->getOption(static::JOBS_OPTION);

        $schedulerTransfer = $this->createSchedulerTransfer($schedulers, $jobs);
        $schedulerResponseTransfer = $this->getFacade()->resume($schedulerTransfer);

        $output->writeln($schedulerResponseTransfer->getMessages());

        return static::CODE_SUCCESS;
    }

    /**
     * @param array $schedulers
     * @param array $jobNames
     *
     * @return \Generated\Shared\Transfer\SchedulerTransfer
     */
    protected function createSchedulerTransfer(array $schedulers, array $jobNames): SchedulerTransfer
    {
        return (new SchedulerTransfer())
            ->setSchedulers($schedulers)
            ->setJobNames($jobNames);
    }
}
