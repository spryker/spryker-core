<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesProductConnector\Persistence;

use Generated\Shared\Transfer\QuoteTransfer;

interface SalesProductConnectorRepositoryInterface
{
    /**
     * @param array<int> $salesOrderItemIds
     *
     * @return array<\Generated\Shared\Transfer\ItemMetadataTransfer>
     */
    public function getSalesOrderItemMetadataByOrderItemIds(array $salesOrderItemIds): array;

    /**
     * @param array<string> $productConcreteSkus
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function getRawProductConcreteTransfersByConcreteSkus(array $productConcreteSkus): array;

    /**
     * @param array<int> $productAbstractIds
     * @param int $interval
     *
     * @return array
     */
    public function getRawProductPopularityByProductAbstractIdsAndInterval(array $productAbstractIds, int $interval): array;

    /**
     * @param int $interval
     *
     * @return array
     */
    public function getProductAbstractIdsForRefreshByInterval(int $interval): array;

    /**
     * Result format:
     * [
     *     $idSalesOrderItem => ['attribute', ...],
     *     ...
     * ]
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array<int, array<string, mixed>>
     */
    public function getSupperAttributesGroupedByIdItem(QuoteTransfer $quoteTransfer): array;
}
