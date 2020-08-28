<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferStockGuiExtension\Dependeency\Plugin;

use Generated\Shared\Transfer\ProductOfferStockTransfer;
use Generated\Shared\Transfer\StoreTransfer;

interface ProductOfferStockTableExpanderPluginInterface
{
    /**
     * Specification:
     * - Returns header title to expand product offer stock table.
     *
     * @api
     *
     * @return string
     */
    public function getHeader(): string;

    /**
     * Specification:
     * - Returns columns data to expand product offer stock table.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferStockTransfer $productOfferStockTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return string
     */
    public function getColumnData(ProductOfferStockTransfer $productOfferStockTransfer, StoreTransfer $storeTransfer): string;
}
