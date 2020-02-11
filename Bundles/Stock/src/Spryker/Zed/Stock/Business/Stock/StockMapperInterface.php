<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Stock\Business\Stock;

interface StockMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\StockTransfer[] $stockTransfers
     *
     * @return string[][]
     */
    public function mapStoresToWarehouses(array $stockTransfers): array;

    /**
     * @param \Generated\Shared\Transfer\StockTransfer[] $stockTransfers
     * @param \Generated\Shared\Transfer\StoreTransfer[] $storeTransfers
     *
     * @return string[][]
     */
    public function mapWarehousesToStores(array $stockTransfers, array $storeTransfers): array;
}
