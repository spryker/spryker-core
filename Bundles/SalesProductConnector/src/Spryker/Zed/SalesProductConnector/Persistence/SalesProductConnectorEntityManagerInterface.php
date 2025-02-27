<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesProductConnector\Persistence;

use Generated\Shared\Transfer\QuoteTransfer;

interface SalesProductConnectorEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param array<int, array<string, mixed>> $supperAttributesGroupedByIdItem
     *
     * @return void
     */
    public function saveItemsMetadata(QuoteTransfer $quoteTransfer, array $supperAttributesGroupedByIdItem): void;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param array<int, array<string, mixed>> $supperAttributesGroupedByIdItem
     *
     * @return void
     */
    public function saveItemsMetadataByFkSalesOrderItem(QuoteTransfer $quoteTransfer, array $supperAttributesGroupedByIdItem): void;

    /**
     * @param list<int> $salesOrderItemIds
     *
     * @return void
     */
    public function deleteSalesOrderItemMetadataCollectionBySalesOrderItemIds(
        array $salesOrderItemIds
    ): void;
}
