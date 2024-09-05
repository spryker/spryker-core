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
class MerchantPayoutReverseCommandByOrderPlugin extends AbstractPlugin implements CommandByOrderInterface
{
    /**
     * {@inheritDoc}
     * - Send a synchronous Request to the PSP App to do refund of previously made money transfers to merchants.
     * - The following properties are required in case of merchant item:
     * - Requires the `ItemTransfer.merchantReference` property to be set.
     * - Requires the `ItemTransfer.orderItemReference` property to be set.
     * - Requires the `ItemTransfer.sumPriceToPayAggregation` property to be set.
     * - Requires the `ItemTransfer.canceledAmount` property to be set.
     * - Requires the `OrderTransfer.orderReference` property to be set.
     * - Requires the `ExpenseTransfer.merchantReference` property to be set.
     * - Requires the `ExpenseTransfer.orderReference` property to be set.
     * - Requires the `ExpenseTransfer.uuid` property to be set.
     * - Requires the `ExpenseTransfer.refundableAmount` property to be set.
     * - Requires the `ExpenseTransfer.canceledAmount` property to be set in case `ExpenseTransfer.refundableAmount` is not set.
     * - If the transfer endpoint URL is not found, returns without performing the transfer.
     * - Fetches the order items and expenses for the transfer.
     * - Calculates the payout reverse amount using {@link \Spryker\Zed\SalesPaymentMerchantExtension\Communication\Dependency\Plugin\MerchantPayoutCalculatorPluginInterface::calculatePayoutAmount} if it's set.
     * - Prepares the order expenses to be transferred if {@link \Spryker\Zed\SalesPaymentMerchant\SalesPaymentMerchantConfig::isOrderExpenseIncludedInPaymentProcess()} is set.
     * - Otherwise, the order expenses are not transferred.
     * - Uses the following configuration to filter out the order expenses for store by type {@link \Spryker\Zed\SalesPaymentMerchant\SalesPaymentMerchantConfig::getExcludedExpenseTypesForStore()}.
     * - The order expenses are not transferred until at least one order item is in the refused state {@link \Spryker\Zed\SalesPaymentMerchant\SalesPaymentMerchantConfig::getItemRefusedStates()}.
     * - Sends the transfer reverse request to the PSP App.
     * - Saves the transfer response to the persistence.
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

        $this->getFacade()->reversePayoutMerchants($salesOrderItemTransfers, $orderTransfer);

        return [];
    }
}
