<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\CustomersRestApi\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @deprecated Use Spryker\Zed\Uuid\Communication\Console\UuidGeneratorConsole instead.
 *
 * @method \Spryker\Zed\CustomersRestApi\Business\CustomersRestApiFacadeInterface getFacade()
 */
class CustomerAddressesUuidWriterConsole extends Console
{
    protected const COMMAND_NAME = 'customer-addresses:uuid:generate';
    protected const COMMAND_DESCRIPTION = 'Generates UUIDs for the existing customers addresses that do not have one.';

    /**
     * @return void
     */
    protected function configure(): void
    {
        parent::configure();
        $this
            ->setName(static::COMMAND_NAME)
            ->setDescription(static::COMMAND_DESCRIPTION)
            ->setHelp('<info>' . static::COMMAND_NAME . ' -h</info>');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int|null
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getFacade()->updateCustomerAddressUuid();
    }
}
