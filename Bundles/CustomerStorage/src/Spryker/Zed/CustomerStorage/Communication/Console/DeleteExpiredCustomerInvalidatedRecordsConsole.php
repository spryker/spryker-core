<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerStorage\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\CustomerStorage\Business\CustomerStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\CustomerStorage\Communication\CustomerStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\CustomerStorage\Persistence\CustomerStorageRepositoryInterface getRepository()
 */
class DeleteExpiredCustomerInvalidatedRecordsConsole extends Console
{
    /**
     * @var string
     */
    protected const COMMAND_NAME = 'customer:delete-expired-customer-invalidated';

    /**
     * @var string
     */
    protected const COMMAND_DESCRIPTION = 'Deletes all expired customer invalidated storage records.';

    /**
     * @return void
     */
    protected function configure(): void
    {
        parent::configure();
        $this->setName(static::COMMAND_NAME);
        $this->setDescription(static::COMMAND_DESCRIPTION);
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->getFacade()->deleteExpiredCustomerInvalidatedStorage();

        return static::CODE_SUCCESS;
    }
}
