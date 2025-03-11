<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesDiscountConnector\Communication\Plugin\MerchantSalesOrder;

use Generated\Shared\Transfer\MerchantOrderTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MerchantSalesOrderExtension\Dependency\Plugin\MerchantOrderTotalsPreRecalculatePluginInterface;

/**
 * @method \Spryker\Zed\SalesDiscountConnector\Business\SalesDiscountConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\SalesDiscountConnector\SalesDiscountConnectorConfig getConfig()
 */
class CopyOrderContextMerchantOrderTotalsPreRecalculatePlugin extends AbstractPlugin implements MerchantOrderTotalsPreRecalculatePluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `MerchantOrderTransfer.order` to be set.
     * - Copies the order context from the original order to the merchant order.
     * - Customer information and original order reference are required for proper discount recalculation.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function preRecalculate(OrderTransfer $orderTransfer, MerchantOrderTransfer $merchantOrderTransfer): OrderTransfer
    {
        return $orderTransfer
            ->setCustomer($merchantOrderTransfer->getOrderOrFail()->getCustomer())
            ->setOrderReference($merchantOrderTransfer->getOrderOrFail()->getOrderReference());
    }
}
