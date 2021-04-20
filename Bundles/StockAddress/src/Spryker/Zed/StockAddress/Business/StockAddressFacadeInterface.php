<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StockAddress\Business;

use Generated\Shared\Transfer\StockCollectionTransfer;
use Generated\Shared\Transfer\StockResponseTransfer;
use Generated\Shared\Transfer\StockTransfer;

interface StockAddressFacadeInterface
{
    /**
     * Specification:
     * - Expands `StockCollectionTransfer.stocks` with `StockAddressTransfer`.
     * - Requires `StockCollectionTransfer.stocks.idStock` to be set.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StockCollectionTransfer $stockCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\StockCollectionTransfer
     */
    public function expandStockCollection(StockCollectionTransfer $stockCollectionTransfer): StockCollectionTransfer;

    /**
     * Specification:
     * - Creates stock address if `StockTransfer.address` is provided.
     * - Requires `StockAddressTransfer.country.idCountry`, `StockAddressTransfer.address1`, `StockAddressTransfer.city`
     *   and `StockAddressTransfer.zipCode` to be set if `StockTransfer.address` is provided.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StockTransfer $stockTransfer
     *
     * @return \Generated\Shared\Transfer\StockResponseTransfer
     */
    public function createStockAddressForStock(StockTransfer $stockTransfer): StockResponseTransfer;

    /**
     * Specification:
     * - Updates stock address if `StockTransfer.address` is provided.
     * - If `StockTransfer.address` is not provided deletes stock address by `idStock`.
     * - Requires `StockTransfer.idStock` to be set.
     * - Requires `StockAddressTransfer.country.idCountry`, `StockAddressTransfer.address1`, `StockAddressTransfer.city`
     *   and `StockAddressTransfer.zipCode` to be set if `StockTransfer.address` is provided.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StockTransfer $stockTransfer
     *
     * @return \Generated\Shared\Transfer\StockResponseTransfer
     */
    public function updateStockAddressForStock(StockTransfer $stockTransfer): StockResponseTransfer;
}
