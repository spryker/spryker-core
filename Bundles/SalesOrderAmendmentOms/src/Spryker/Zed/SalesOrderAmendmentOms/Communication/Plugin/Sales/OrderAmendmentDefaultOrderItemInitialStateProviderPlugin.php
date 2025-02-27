<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendmentOms\Communication\Plugin\Sales;

use Generated\Shared\Transfer\OmsOrderItemStateTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesExtension\Dependency\Plugin\OrderItemInitialStateProviderPluginInterface;

/**
 * @method \Spryker\Zed\SalesOrderAmendmentOms\Business\SalesOrderAmendmentOmsFacadeInterface getFacade()
 * @method \Spryker\Zed\SalesOrderAmendmentOms\Business\SalesOrderAmendmentOmsBusinessFactory getBusinessFactory()
 * @method \Spryker\Zed\SalesOrderAmendmentOms\SalesOrderAmendmentOmsConfig getConfig()
 */
class OrderAmendmentDefaultOrderItemInitialStateProviderPlugin extends AbstractPlugin implements OrderItemInitialStateProviderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Returns initial oms order item state for order items in order amendment flow.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return \Generated\Shared\Transfer\OmsOrderItemStateTransfer|null
     */
    public function getInitialItemState(
        QuoteTransfer $quoteTransfer,
        SaveOrderTransfer $saveOrderTransfer
    ): ?OmsOrderItemStateTransfer {
        return $this->getBusinessFactory()
            ->createOmsOrderItemStateReader()
            ->getInitialItemState($quoteTransfer, $saveOrderTransfer);
    }
}
