<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StockAddress\Communication\Plugin\Stock;

use Generated\Shared\Transfer\StockCollectionTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\StockExtension\Dependency\Plugin\StockCollectionExpanderPluginInterface;

/**
 * @method \Spryker\Zed\StockAddress\Business\StockAddressFacadeInterface getFacade()
 * @method \Spryker\Zed\StockAddress\StockAddressConfig getConfig()
 */
class StockAddressStockCollectionExpanderPlugin extends AbstractPlugin implements StockCollectionExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands StockCollection with StockAddress data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StockCollectionTransfer $stockCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\StockCollectionTransfer
     */
    public function expandStockCollection(StockCollectionTransfer $stockCollectionTransfer): StockCollectionTransfer
    {
        return $this->getFacade()->expandStockCollection($stockCollectionTransfer);
    }
}
