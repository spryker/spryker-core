<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StockAddress\Persistence;

use Generated\Shared\Transfer\StockAddressTransfer;

interface StockAddressEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\StockAddressTransfer $stockAddressTransfer
     *
     * @return \Generated\Shared\Transfer\StockAddressTransfer
     */
    public function saveStockAddress(StockAddressTransfer $stockAddressTransfer): StockAddressTransfer;

    /**
     * @param int $idStock
     *
     * @return void
     */
    public function deleteStockAddressForStock(int $idStock): void;
}
