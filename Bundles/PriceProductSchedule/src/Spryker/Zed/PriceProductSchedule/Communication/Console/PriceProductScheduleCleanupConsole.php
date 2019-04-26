<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\PriceProductSchedule\Business\PriceProductScheduleFacadeInterface getFacade()
 * @method \Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleRepositoryInterface getRepository()
 */
class PriceProductScheduleCleanupConsole extends Console
{
    public const COMMAND_NAME = 'price-product-schedule:clean-up';
    public const DESCRIPTION = 'Deletes scheduled prices that has been applied earlier than the days provided as argument';
    public const DAYS_RETAINED = 'days retained';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName(static::COMMAND_NAME)
            ->setDescription(static::DESCRIPTION);

        $this->addArgument(
            static::DAYS_RETAINED,
            InputArgument::REQUIRED,
            'Delete scheduled prices that has been applied earlier than count of days'
        );
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int|null
     */
    protected function execute(InputInterface $input, OutputInterface $output): ?int
    {
        $daysRetained = (int)$this->input->getArgument(static::DAYS_RETAINED);
        $this->getFacade()->cleanAppliedScheduledPrices($daysRetained);

        return static::CODE_SUCCESS;
    }
}
