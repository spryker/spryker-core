<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendmentOms\Communication\Plugin\Sales;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesExtension\Dependency\Plugin\OrderExpanderPluginInterface;

/**
 * @method \Spryker\Zed\SalesOrderAmendmentOms\SalesOrderAmendmentOmsConfig getConfig()
 * @method \Spryker\Zed\SalesOrderAmendmentOms\Business\SalesOrderAmendmentOmsFacadeInterface getFacade()
 * @method \Spryker\Zed\SalesOrderAmendmentOms\Communication\SalesOrderAmendmentOmsCommunicationFactory getFactory()
 */
class IsAmendableOrderExpanderPlugin extends AbstractPlugin implements OrderExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `OrderTransfer.orderReference` to be set.
     * - Checks if all order items are in order item state that has a flag defined in {@link \Spryker\Zed\SalesOrderAmendmentOms\SalesOrderAmendmentOmsConfig::getAmendableOmsFlag()}.
     * - Expands the `OrderTransfer.isAmendable` property.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function hydrate(OrderTransfer $orderTransfer): OrderTransfer
    {
        $orderTransfers = $this->getFacade()->expandOrdersWithIsAmendable([$orderTransfer]);

        /** @phpstan-var \Generated\Shared\Transfer\OrderTransfer */
        return array_shift($orderTransfers);
    }
}
