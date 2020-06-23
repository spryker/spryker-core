<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesInvoice\Communication\Console;

use Generated\Shared\Transfer\OrderInvoiceSendRequestTransfer;
use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\SalesInvoice\Business\SalesInvoiceFacadeInterface getFacade()
 * @method \Spryker\Zed\SalesInvoice\Persistence\SalesInvoiceRepositoryInterface getRepository()
 * @method \Spryker\Zed\SalesInvoice\Communication\SalesInvoiceCommunicationFactory getFactory()
 */
class OrderInvoiceSendConsole extends Console
{
    protected const COMMAND_NAME = 'order:invoice:send';
    protected const ADD_DESCRIPTION_HERE = 'ADD DESCRIPTION HERE';

    protected const ARGUMENT_ORDER_IDS = 'order_ids';
    protected const ARGUMENT_FORCE_EMAIL_SEND = 'force';
    protected const BATCH = 20;

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this
            ->setName(static::COMMAND_NAME)
            ->setDescription(static::ADD_DESCRIPTION_HERE);

        $this->addArgument(
            static::ARGUMENT_ORDER_IDS,
            InputArgument::OPTIONAL,
            ''
        );
        $this->addOption(
            static::ARGUMENT_FORCE_EMAIL_SEND
        );
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $orderIds = $input->getArgument(static::ARGUMENT_ORDER_IDS);
        $force = $input->getOption(static::ARGUMENT_FORCE_EMAIL_SEND);

        $orderInvoiceSendRequestTransfer = (new OrderInvoiceSendRequestTransfer())
            ->setBatch(static::BATCH);

        if ($orderIds) {
            $orderInvoiceSendRequestTransfer->setSalesOrderIds((array)$orderIds)
                ->setBatch(count($orderIds));
        }

        if ($force) {
            $orderInvoiceSendRequestTransfer->setForce($force);
        }

        do {
            $orderInvoiceSendResponseTransfer = $this->getFacade()
                ->sendOrderInvoices($orderInvoiceSendRequestTransfer);
            if ($orderIds) {
                break;
            }
        } while ($orderInvoiceSendResponseTransfer->getCount() === $orderInvoiceSendRequestTransfer->getBatch());

        return static::CODE_SUCCESS;
    }
}
