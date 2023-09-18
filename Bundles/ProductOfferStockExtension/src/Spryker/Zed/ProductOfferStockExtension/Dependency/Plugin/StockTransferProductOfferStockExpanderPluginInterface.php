<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferStockExtension\Dependency\Plugin;

use Generated\Shared\Transfer\StockTransfer;

/**
 * Provides extension capabilities for expanding/modification `Stock` data within `ProductOfferStock`.
 */
interface StockTransferProductOfferStockExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands `ProductOfferStockTransfer.Stock` with necessary data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StockTransfer $stockTransfer
     *
     * @return \Generated\Shared\Transfer\StockTransfer
     */
    public function expand(StockTransfer $stockTransfer): StockTransfer;
}
