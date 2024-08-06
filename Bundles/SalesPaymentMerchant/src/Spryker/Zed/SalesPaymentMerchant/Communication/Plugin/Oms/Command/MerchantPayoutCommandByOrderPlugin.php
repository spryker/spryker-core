<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPaymentMerchant\Communication\Plugin\Oms\Command;

use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use Spryker\Zed\Oms\Dependency\Plugin\Command\CommandByOrderInterface;

/**
 * @method \Spryker\Zed\SalesPaymentMerchant\SalesPaymentMerchantConfig getConfig()
 * @method \Spryker\Zed\SalesPaymentMerchant\Business\SalesPaymentMerchantFacadeInterface getFacade()
 * @method \Spryker\Zed\SalesPaymentMerchant\Communication\SalesPaymentMerchantCommunicationFactory getFactory()
 */
class MerchantPayoutCommandByOrderPlugin extends AbstractPlugin implements CommandByOrderInterface
{
    /**
     * {@inheritDoc}
     * - Sends a synchronous request to the PSP App to perform money transfers to merchants.
     * - The following properties are required in case of merchant item:
     * - Requires the `ItemTransfer.merchantReference` property to be set.
     * - Requires the `OrderTransfer.orderReference` property to be set.
     * - Requires the `ItemTransfer.orderItemReference` property to be set.
     * - Requires the `ItemTransfer.sumPriceToPayAggregation` property to be set.
     * - Requires the `ExpenseTransfer.merchantReference` property to be set.
     * - Requires the `ExpenseTransfer.orderReference` property to be set.
     * - Requires the `ExpenseTransfer.uuid` property to be set.
     * - Requires the `ExpenseTransfer.sumPriceToPayAggregation` property to be set.
     * - If the transfer endpoint URL is not found, returns without performing the transfer.
     * - Fetches the order items and expenses for the transfer.
     * - Calculates the payout amount using {@link \Spryker\Zed\SalesPaymentMerchantExtension\Communication\Dependency\Plugin\MerchantPayoutCalculatorPluginInterface::calculatePayoutAmount} if it's set.
     * - Sends the transfer request to the PSP App.
     * - Saves the transfer response to persistence.
     *
     * @api
     *
     * @param array<\Orm\Zed\Sales\Persistence\SpySalesOrderItem> $orderItems
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     * @param \Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject $data
     *
     * @return array<mixed>
     */
    public function run(array $orderItems, SpySalesOrder $orderEntity, ReadOnlyArrayObject $data)
    {
        $orderTransfer = $this->getFactory()
            ->createSalesOrderReader()
            ->getOrderTransfer($orderEntity->getIdSalesOrder());

        $salesOrderItemTransfers = $this->getFactory()
            ->createSalesOrderItemExtractor()
            ->extractSalesOrderItemsFromOrderBySalesOrderItemIds($orderItems, $orderTransfer);

        $this->getFacade()->payoutMerchants($salesOrderItemTransfers, $orderTransfer);

        return [];
    }
}
