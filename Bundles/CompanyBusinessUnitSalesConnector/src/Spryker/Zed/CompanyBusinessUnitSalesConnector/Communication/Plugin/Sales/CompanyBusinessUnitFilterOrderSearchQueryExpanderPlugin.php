<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitSalesConnector\Communication\Plugin\Sales;

use Generated\Shared\Transfer\OrderListTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesExtension\Dependency\Plugin\OrderSearchQueryExpanderPluginInterface;

/**
 * @method \Spryker\Zed\CompanyBusinessUnitSalesConnector\Business\CompanyBusinessUnitSalesConnectorFacadeInterface getFacade()
 */
class CompanyBusinessUnitFilterOrderSearchQueryExpanderPlugin extends AbstractPlugin implements OrderSearchQueryExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Returns true if filtering by company business unit could be applied, false otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return bool
     */
    public function isApplicable(OrderListTransfer $orderListTransfer): bool
    {
        return $this->getFacade()->isCompanyBusinessUnitFilterApplicable(
            $orderListTransfer->getFilterFields()->getArrayCopy()
        );
    }

    /**
     * {@inheritDoc}
     * - Expands OrderListTransfer::queryJoins with additional QueryJoinTransfer to filter by company business unit.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function expand(OrderListTransfer $orderListTransfer): OrderListTransfer
    {
        $queryJoinCollectionTransfer = $this->getFacade()->expandQueryJoinCollectionWithCompanyBusinessUnitFilter(
            $orderListTransfer->getFilterFields()->getArrayCopy(),
            $orderListTransfer->getQueryJoins()
        );

        return $orderListTransfer->setQueryJoins($queryJoinCollectionTransfer);
    }
}
