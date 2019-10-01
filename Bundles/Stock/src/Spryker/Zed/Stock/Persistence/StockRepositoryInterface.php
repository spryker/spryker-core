<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Stock\Persistence;

use Generated\Shared\Transfer\StockCriteriaFilterTransfer;

interface StockRepositoryInterface
{
    /**
     * @return \Generated\Shared\Transfer\StockTransfer[]
     */
    public function getStockNames(): array;

    /**
     * @param string $storeName
     *
     * @return string[]
     */
    public function getStockNamesForStore(string $storeName): array;

    /**
     * @param \Generated\Shared\Transfer\StockCriteriaFilterTransfer $stockCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\StockTransfer[]
     */
    public function getStocksWithRelatedStoresByCriteriaFilter(StockCriteriaFilterTransfer $stockCriteriaFilterTransfer): array;
}
