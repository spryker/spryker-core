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
    protected const COMMAND_DESCRIPTION = 'Sends email for order invoices';

    protected const ARGUMENT_ORDER_IDS = 'order_ids';
    protected const ARGUMENT_ORDER_IDS_DESCRIPTION = 'Filter order invoices by order ids';

    protected const ARGUMENT_FORCE_EMAIL_SEND = 'force';
    protected const ARGUMENT_FORCE_EMAIL_SEND_DESCRIPTION = 'Allows to resend email';

    protected const BATCH = 20;

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this
            ->setName(static::COMMAND_NAME)
            ->setDescription(static::COMMAND_DESCRIPTION);

        $this->addArgument(
            static::ARGUMENT_ORDER_IDS,
            InputArgument::OPTIONAL,
            static::ARGUMENT_ORDER_IDS_DESCRIPTION
        );
        $this->addOption(
            static::ARGUMENT_FORCE_EMAIL_SEND,
            null,
            null,
            static::ARGUMENT_FORCE_EMAIL_SEND_DESCRIPTION
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
        $orderIds = $this->getArgumentOrderIdsValue($input);
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
        } while (!$orderIds && $orderInvoiceSendResponseTransfer->getCount() === $orderInvoiceSendRequestTransfer->getBatch());

        return static::CODE_SUCCESS;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     *
     * @return int[]
     */
    protected function getArgumentOrderIdsValue(InputInterface $input): array
    {
        return array_map('intval', (array)$input->getArgument(static::ARGUMENT_ORDER_IDS));
    }
}
