<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Scheduler\Communication\Console;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\Scheduler\Business\SchedulerFacadeInterface getFacade()
 * @method \Spryker\Zed\Scheduler\Communication\SchedulerCommunicationFactory getFactory()
 */
class SchedulerSuspendConsole extends AbstractSchedulerConsole
{
    public const COMMAND_NAME = 'scheduler:suspend';
    public const DESCRIPTION = 'Suspends scheduler job(s)';

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
        $jobNames = $input->getOption(static::JOBS_OPTION);

        $schedulerFilterTransfer = $this->getFactory()
            ->createSchedulerFilterBuilder()
            ->withSchedulerIds($schedulers)
            ->withJobNames($jobNames)
            ->build();

        $schedulerResponseCollectionTransfer = $this->getFacade()->suspend($schedulerFilterTransfer);

        $this->outputCommandResponse($schedulerResponseCollectionTransfer, $output);

        return static::CODE_SUCCESS;
    }
}
