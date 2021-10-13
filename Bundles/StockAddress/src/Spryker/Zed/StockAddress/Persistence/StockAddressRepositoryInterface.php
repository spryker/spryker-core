<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StockAddress\Persistence;

interface StockAddressRepositoryInterface
{
    /**
     * @param array<int> $stockIds
     *
     * @return array<\Generated\Shared\Transfer\StockAddressTransfer>
     */
    public function getStockAddressesByStockIds(array $stockIds): array;

    /**
     * @param int $idStock
     *
     * @return bool
     */
    public function isStockAddressExistsForStock(int $idStock): bool;
}
