<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendment\Dependency\Facade;

use Generated\Shared\Transfer\OrderListRequestTransfer;
use Generated\Shared\Transfer\OrderListTransfer;
use Generated\Shared\Transfer\SalesOrderItemCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\SalesOrderItemCollectionRequestTransfer;
use Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer;

interface SalesOrderAmendmentToSalesFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderListRequestTransfer $orderListRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function getOffsetPaginatedCustomerOrderList(OrderListRequestTransfer $orderListRequestTransfer): OrderListTransfer;

    /**
     * @param \Generated\Shared\Transfer\SalesOrderItemCollectionRequestTransfer $salesOrderItemCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer
     */
    public function createSalesOrderItemCollectionByQuote(
        SalesOrderItemCollectionRequestTransfer $salesOrderItemCollectionRequestTransfer
    ): SalesOrderItemCollectionResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\SalesOrderItemCollectionRequestTransfer $salesOrderItemCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer
     */
    public function updateSalesOrderItemCollectionByQuote(
        SalesOrderItemCollectionRequestTransfer $salesOrderItemCollectionRequestTransfer
    ): SalesOrderItemCollectionResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\SalesOrderItemCollectionDeleteCriteriaTransfer $salesOrderItemCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer
     */
    public function deleteSalesOrderItemCollection(
        SalesOrderItemCollectionDeleteCriteriaTransfer $salesOrderItemCollectionDeleteCriteriaTransfer
    ): SalesOrderItemCollectionResponseTransfer;
}
