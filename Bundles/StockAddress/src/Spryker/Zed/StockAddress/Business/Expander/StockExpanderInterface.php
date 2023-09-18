<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StockAddress\Business\Expander;

use Generated\Shared\Transfer\StockTransfer;

interface StockExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\StockTransfer $stockTransfer
     *
     * @return \Generated\Shared\Transfer\StockTransfer
     */
    public function expandStockTransfer(StockTransfer $stockTransfer): StockTransfer;
}
