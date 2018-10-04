<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityGui\Dependency\Facade;

use Generated\Shared\Transfer\StockProductTransfer;
use Generated\Shared\Transfer\StoreTransfer;

class AvailabilityGuiToStockBridge implements AvailabilityGuiToStockInterface
{
    /**
     * @var \Spryker\Zed\Stock\Business\StockFacadeInterface
     */
    protected $stockFacade;

    /**
     * @param \Spryker\Zed\Stock\Business\StockFacadeInterface $stockFacade
     */
    public function __construct($stockFacade)
    {
        $this->stockFacade = $stockFacade;
    }

    /**
     * @param string $sku
     *
     * @return int
     */
    public function calculateStockForProduct($sku)
    {
        return $this->stockFacade->calculateStockForProduct($sku);
    }

    /**
     * @param string $sku
     *
     * @return bool
     */
    public function isNeverOutOfStock($sku)
    {
        return $this->stockFacade->isNeverOutOfStock($sku);
    }

    /**
     * @param \Generated\Shared\Transfer\StockProductTransfer $transferStockProduct
     *
     * @return int
     */
    public function createStockProduct(StockProductTransfer $transferStockProduct)
    {
        return $this->stockFacade->createStockProduct($transferStockProduct);
    }

    /**
     * @param \Generated\Shared\Transfer\StockProductTransfer $stockProductTransfer
     *
     * @return int
     */
    public function updateStockProduct(StockProductTransfer $stockProductTransfer)
    {
        return $this->stockFacade->updateStockProduct($stockProductTransfer);
    }

    /**
     * @return string[]
     */
    public function getAvailableStockTypes()
    {
        return $this->stockFacade->getAvailableStockTypes();
    }

    /**
     * @param int $idProductConcrete
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StockProductTransfer[]
     */
    public function findStockProductsByIdProductForStore($idProductConcrete, StoreTransfer $storeTransfer)
    {
        return $this->stockFacade->findStockProductsByIdProductForStore($idProductConcrete, $storeTransfer);
    }

    /**
     * @return array
     */
    public function getWarehouseToStoreMapping()
    {
        return $this->stockFacade->getWarehouseToStoreMapping();
    }

    /**
     * @return array
     */
    public function getStoreToWarehouseMapping()
    {
        return $this->stockFacade->getStoreToWarehouseMapping();
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return array
     */
    public function getStockTypesForStore(StoreTransfer $storeTransfer)
    {
        return $this->stockFacade->getStockTypesForStore($storeTransfer);
    }
}
