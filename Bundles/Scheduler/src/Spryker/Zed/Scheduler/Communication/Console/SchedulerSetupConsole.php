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
class SchedulerSetupConsole extends AbstractSchedulerConsole
{
    /**
     * @var string
     */
    public const COMMAND_NAME = 'scheduler:setup';
    /**
     * @var string
     */
    public const DESCRIPTION = 'Sets up scheduler cron jobs.';

    /**
     * @var string
     */
    protected const ROLES_OPTION = 'roles';
    /**
     * @var string
     */
    protected const ROLES_OPTION_SHORTCUT = 'r';
    /**
     * @var string
     */
    protected const ROLES_OPTION_DESCRIPTION = 'Job roles to include.';

    /**
     * @var string
     */
    protected const SCHEDULERS_OPTION = 'schedulers';
    /**
     * @var string
     */
    protected const SCHEDULERS_OPTION_SHORTCUT = 's';
    /**
     * @var string
     */
    protected const SCHEDULERS_OPTION_DESCRIPTION = 'Schedulers that will be executed.';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription(self::DESCRIPTION);

        $this->addOption(
            static::ROLES_OPTION,
            static::ROLES_OPTION_SHORTCUT,
            InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
            static::ROLES_OPTION_DESCRIPTION,
            []
        );

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
        $roles = $input->getOption(static::ROLES_OPTION);
        $schedulers = $input->getOption(static::SCHEDULERS_OPTION);

        $schedulerFilterTransfer = $this->createSchedulerFilterTransfer($roles, $schedulers);
        $responseCollectionTransfer = $this->getFacade()->setup($schedulerFilterTransfer);

        return $this->outputCommandResponseStatus($responseCollectionTransfer, $output);
    }
}
