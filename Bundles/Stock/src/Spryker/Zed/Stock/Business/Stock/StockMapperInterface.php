<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Stock\Business\Stock;

interface StockMapperInterface
{
    /**
     * @param array<\Generated\Shared\Transfer\StockTransfer> $stockTransfers
     *
     * @return array<array<string>>
     */
    public function mapStoresToWarehouses(array $stockTransfers): array;

    /**
     * @param array<\Generated\Shared\Transfer\StockTransfer> $stockTransfers
     * @param array<\Generated\Shared\Transfer\StoreTransfer> $storeTransfers
     *
     * @return array<array<string>>
     */
    public function mapWarehousesToStores(array $stockTransfers, array $storeTransfers): array;
}
