<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StockAddress\Communication\Plugin\Stock;

use Generated\Shared\Transfer\StockTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductOfferStockExtension\Dependency\Plugin\StockTransferProductOfferStockExpanderPluginInterface;

/**
 * @method \Spryker\Zed\StockAddress\StockAddressConfig getConfig()
 * @method \Spryker\Zed\StockAddress\Business\StockAddressFacadeInterface getFacade()
 */
class StockAddressStockTransferProductOfferStockExpanderPlugin extends AbstractPlugin implements StockTransferProductOfferStockExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands `StockTransfer.address` with `StockAddressTransfer`.
     * - If `StockTransfer.idStock` is not set the transfer is not expanded.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StockTransfer $stockTransfer
     *
     * @return \Generated\Shared\Transfer\StockTransfer
     */
    public function expand(StockTransfer $stockTransfer): StockTransfer
    {
        return $this->getFacade()->expandStockTransferWithStockAddress($stockTransfer);
    }
}
