<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesProductConnector\Persistence;

interface SalesProductConnectorRepositoryInterface
{
    /**
     * @param int[] $salesOrderItemIds
     *
     * @return \Generated\Shared\Transfer\ItemMetadataTransfer[]
     */
    public function getSalesOrderItemMetadataByOrderItemIds(array $salesOrderItemIds): array;

    /**
     * @param string[] $productConcreteSkus
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    public function getRawProductConcreteTransfersByConcreteSkus(array $productConcreteSkus): array;
}
