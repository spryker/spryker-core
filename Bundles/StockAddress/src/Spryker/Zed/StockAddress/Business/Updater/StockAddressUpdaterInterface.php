<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StockAddress\Business\Updater;

use Generated\Shared\Transfer\StockResponseTransfer;
use Generated\Shared\Transfer\StockTransfer;

interface StockAddressUpdaterInterface
{
    /**
     * @param \Generated\Shared\Transfer\StockTransfer $stockTransfer
     *
     * @return \Generated\Shared\Transfer\StockResponseTransfer
     */
    public function updateStockAddressForStock(StockTransfer $stockTransfer): StockResponseTransfer;
}
