<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StockAddress\Communication\Plugin\Stock;

use Generated\Shared\Transfer\StockResponseTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\StockExtension\Dependency\Plugin\StockPostCreatePluginInterface;

/**
 * @method \Spryker\Zed\StockAddress\Business\StockAddressFacadeInterface getFacade()
 * @method \Spryker\Zed\StockAddress\StockAddressConfig getConfig()
 */
class StockAddressStockPostCreatePlugin extends AbstractPlugin implements StockPostCreatePluginInterface
{
    /**
     * {@inheritDoc}
     * - Creates stock address if `StockTransfer.address` is provided.
     * - Requires `StockAddress.country.idCountry` to be set if `StockTransfer.address` is provided.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StockTransfer $stockTransfer
     *
     * @return \Generated\Shared\Transfer\StockResponseTransfer
     */
    public function postCreate(StockTransfer $stockTransfer): StockResponseTransfer
    {
        return $this->getFacade()->createStockAddressForStock($stockTransfer);
    }
}
