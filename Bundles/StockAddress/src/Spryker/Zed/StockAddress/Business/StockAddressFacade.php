<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StockAddress\Business;

use Generated\Shared\Transfer\StockCollectionTransfer;
use Generated\Shared\Transfer\StockResponseTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\StockAddress\Business\StockAddressBusinessFactory getFactory()
 * @method \Spryker\Zed\StockAddress\Persistence\StockAddressRepositoryInterface getRepository()
 * @method \Spryker\Zed\StockAddress\Persistence\StockAddressEntityManagerInterface getEntityManager()
 */
class StockAddressFacade extends AbstractFacade implements StockAddressFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StockCollectionTransfer $stockCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\StockCollectionTransfer
     */
    public function expandStockCollection(StockCollectionTransfer $stockCollectionTransfer): StockCollectionTransfer
    {
        return $this->getFactory()
            ->createStockCollectionExpander()
            ->expandStockCollection($stockCollectionTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StockTransfer $stockTransfer
     *
     * @return \Generated\Shared\Transfer\StockResponseTransfer
     */
    public function createStockAddressForStock(StockTransfer $stockTransfer): StockResponseTransfer
    {
        return $this->getFactory()
            ->createStockAddressCreator()
            ->createStockAddressForStock($stockTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StockTransfer $stockTransfer
     *
     * @return \Generated\Shared\Transfer\StockResponseTransfer
     */
    public function updateStockAddressForStock(StockTransfer $stockTransfer): StockResponseTransfer
    {
        return $this->getFactory()
            ->createStockAddressUpdater()
            ->updateStockAddressForStock($stockTransfer);
    }
}
