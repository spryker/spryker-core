<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendmentOms\Communication\Plugin\Sales;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesExtension\Dependency\Plugin\SearchOrderExpanderPluginInterface;

/**
 * @method \Spryker\Zed\SalesOrderAmendmentOms\Business\SalesOrderAmendmentOmsFacadeInterface getFacade()
 * @method \Spryker\Zed\SalesOrderAmendmentOms\SalesOrderAmendmentOmsConfig getConfig()
 * @method \Spryker\Zed\SalesOrderAmendmentOms\Communication\SalesOrderAmendmentOmsCommunicationFactory getFactory()
 */
class IsAmendableOrderSearchOrderExpanderPlugin extends AbstractPlugin implements SearchOrderExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `OrderTransfer.orderReference` to be set for each order in the provided array.
     * - Iterates through the provided array of `OrderTransfer` objects.
     * - For each order checks if all order items are in order item state that has a flag defined in {@link \Spryker\Zed\SalesOrderAmendmentOms\SalesOrderAmendmentOmsConfig::getAmendableOmsFlag()}.
     * - Expands the `OrderTransfer.isAmendable` property.
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\OrderTransfer> $orderTransfers
     *
     * @return list<\Generated\Shared\Transfer\OrderTransfer>
     */
    public function expand(array $orderTransfers): array
    {
        return $this->getFacade()->expandOrdersWithIsAmendable($orderTransfers);
    }
}
