<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitSalesConnector\Communication\Plugin\Sales;

use Generated\Shared\Transfer\QueryJoinCollectionTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesExtension\Dependency\Plugin\OrderSearchQueryExpanderPluginInterface;

/**
 * @method \Spryker\Zed\CompanyBusinessUnitSalesConnector\Business\CompanyBusinessUnitSalesConnectorFacadeInterface getFacade()
 */
class CompanyUserFilterOrderSearchQueryExpanderPlugin extends AbstractPlugin implements OrderSearchQueryExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Returns true if filtering by company user name and email could be applied, false otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FilterFieldTransfer[] $filterFieldTransfers
     *
     * @return bool
     */
    public function isApplicable(array $filterFieldTransfers): bool
    {
        return $this->getFacade()->isCompanyUserFilterApplicable($filterFieldTransfers);
    }

    /**
     * {@inheritDoc}
     * - Expands QueryJoinCollectionTransfer with additional QueryJoinTransfers to filter by company user name and email.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FilterFieldTransfer[] $filterFieldTransfers
     * @param \Generated\Shared\Transfer\QueryJoinCollectionTransfer $queryJoinCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\QueryJoinCollectionTransfer
     */
    public function expand(
        array $filterFieldTransfers,
        QueryJoinCollectionTransfer $queryJoinCollectionTransfer
    ): QueryJoinCollectionTransfer {
        return $this->getFacade()->expandQueryJoinCollectionWithCompanyUserFilter(
            $filterFieldTransfers,
            $queryJoinCollectionTransfer
        );
    }
}
