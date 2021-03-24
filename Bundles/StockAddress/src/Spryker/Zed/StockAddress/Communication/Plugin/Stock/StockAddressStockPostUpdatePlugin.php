<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StockAddress\Communication\Plugin\Stock;

use Generated\Shared\Transfer\StockResponseTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\StockExtension\Dependency\Plugin\StockPostUpdatePluginInterface;

/**
 * @method \Spryker\Zed\StockAddress\Business\StockAddressFacadeInterface getFacade()
 * @method \Spryker\Zed\StockAddress\StockAddressConfig getConfig()
 */
class StockAddressStockPostUpdatePlugin extends AbstractPlugin implements StockPostUpdatePluginInterface
{
    /**
     * {@inheritDoc}
     * - Updates Stock Address if `StockTransfer.Address` is provided.
     * - If `StockTransfer.Address` is not provided deletes `StockAddress` by `idStock`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StockTransfer $stockTransfer
     *
     * @return \Generated\Shared\Transfer\StockResponseTransfer
     */
    public function postUpdate(StockTransfer $stockTransfer): StockResponseTransfer
    {
        return $this->getFacade()->updateStockAddressForStock($stockTransfer);
    }
}
