<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanySalesConnector\Business;

use Generated\Shared\Transfer\QueryJoinCollectionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;

interface CompanySalesConnectorFacadeInterface
{
    /**
     * Specification:
     * - Expands sales order with company uuid and persists updated entity.
     * - Requires SaveOrderTransfer::idSalesOrder to be set.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function updateOrderCompanyUuid(SaveOrderTransfer $saveOrderTransfer, QuoteTransfer $quoteTransfer): void;

    /**
     * Specification:
     * - Expands QueryJoinCollectionTransfer with additional QueryJoinTransfer to filter by company.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FilterFieldTransfer[] $filterFieldTransfers
     * @param \Generated\Shared\Transfer\QueryJoinCollectionTransfer $queryJoinCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\QueryJoinCollectionTransfer
     */
    public function expandQueryJoinCollectionWithCompanyFilter(
        array $filterFieldTransfers,
        QueryJoinCollectionTransfer $queryJoinCollectionTransfer
    ): QueryJoinCollectionTransfer;

    /**
     * Specification:
     * - Expands QueryJoinCollectionTransfer with additional QueryJoinTransfers to filter by customer name and email.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FilterFieldTransfer[] $filterFieldTransfers
     * @param \Generated\Shared\Transfer\QueryJoinCollectionTransfer $queryJoinCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\QueryJoinCollectionTransfer
     */
    public function expandQueryJoinCollectionWithCustomerFilter(
        array $filterFieldTransfers,
        QueryJoinCollectionTransfer $queryJoinCollectionTransfer
    ): QueryJoinCollectionTransfer;

    /**
     * Specification:
     * - Expands QueryJoinCollectionTransfer with additional QueryJoinTransfers to sort by customer name or email.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FilterFieldTransfer[] $filterFieldTransfers
     * @param \Generated\Shared\Transfer\QueryJoinCollectionTransfer $queryJoinCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\QueryJoinCollectionTransfer
     */
    public function expandQueryJoinCollectionWithCustomerSorting(
        array $filterFieldTransfers,
        QueryJoinCollectionTransfer $queryJoinCollectionTransfer
    ): QueryJoinCollectionTransfer;

    /**
     * Specification:
     * - Returns true if filter with specific type set, false otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FilterFieldTransfer[] $filterFieldTransfers
     * @param string $type
     *
     * @return bool
     */
    public function isFilterFieldSet(array $filterFieldTransfers, string $type): bool;
}
