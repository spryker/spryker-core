<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Stock\Business;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\StockProductTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Generated\Shared\Transfer\TypeTransfer;
use Spryker\DecimalObject\Decimal;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Stock\Business\StockBusinessFactory getFactory()
 */
class StockFacade extends AbstractFacade implements StockFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $sku
     *
     * @return bool
     */
    public function isNeverOutOfStock($sku)
    {
        return $this->getFactory()->createReaderModel()->isNeverOutOfStock($sku);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return bool
     */
    public function isNeverOutOfStockForStore($sku, StoreTransfer $storeTransfer)
    {
        return $this->getFactory()->createReaderModel()->isNeverOutOfStockForStore($sku, $storeTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $sku
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    public function calculateStockForProduct(string $sku): Decimal
    {
        return $this->getFactory()->createCalculatorModel()->calculateStockForProduct($sku);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    public function calculateProductStockForStore(string $sku, StoreTransfer $storeTransfer): Decimal
    {
        return $this->getFactory()->createCalculatorModel()->calculateProductStockForStore($sku, $storeTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\TypeTransfer $stockTypeTransfer
     *
     * @return int
     */
    public function createStockType(TypeTransfer $stockTypeTransfer)
    {
        return $this->getFactory()->createWriterModel()->createStockType($stockTypeTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StockProductTransfer $transferStockProduct
     *
     * @return int
     */
    public function createStockProduct(StockProductTransfer $transferStockProduct)
    {
        return $this->getFactory()->createWriterModel()->createStockProduct($transferStockProduct);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StockProductTransfer $stockProductTransfer
     *
     * @return int
     */
    public function updateStockProduct(StockProductTransfer $stockProductTransfer)
    {
        return $this->getFactory()->createWriterModel()->updateStockProduct($stockProductTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $sku
     * @param string $stockType
     * @param int $decrementBy
     *
     * @return void
     */
    public function decrementStockProduct($sku, $stockType, $decrementBy = 1)
    {
        $this->getFactory()->createWriterModel()->decrementStock($sku, $stockType, $decrementBy);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $sku
     * @param string $stockType
     * @param int $incrementBy
     *
     * @return void
     */
    public function incrementStockProduct($sku, $stockType, $incrementBy = 1)
    {
        $this->getFactory()->createWriterModel()->incrementStock($sku, $stockType, $incrementBy);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $sku
     * @param string $stockType
     *
     * @return bool
     */
    public function hasStockProduct($sku, $stockType)
    {
        return $this->getFactory()->createReaderModel()->hasStockProduct($sku, $stockType);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function persistStockProductCollection(ProductConcreteTransfer $productConcreteTransfer)
    {
        return $this->getFactory()
            ->createWriterModel()
            ->persistStockProductCollection($productConcreteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function expandProductConcreteWithStocks(ProductConcreteTransfer $productConcreteTransfer)
    {
        return $this->getFactory()
            ->createReaderModel()
            ->expandProductConcreteWithStocks($productConcreteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string[]
     */
    public function getAvailableStockTypes()
    {
         return $this->getFactory()->createReaderModel()->getStockTypes();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\StockProductTransfer[]
     */
    public function getStockProductsByIdProduct($idProductConcrete)
    {
        return $this->getFactory()
            ->createReaderModel()
            ->getStockProductsByIdProduct($idProductConcrete);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductConcrete
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StockProductTransfer[]
     */
    public function findStockProductsByIdProductForStore($idProductConcrete, StoreTransfer $storeTransfer)
    {
        return $this->getFactory()
            ->createReaderModel()
            ->findStockProductsByIdProductForStore($idProductConcrete, $storeTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return array
     */
    public function getStockTypesForStore(StoreTransfer $storeTransfer)
    {
        return $this->getFactory()->createReaderModel()->getStockTypesForStore($storeTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return array
     */
    public function getWarehouseToStoreMapping()
    {
        return $this->getFactory()
            ->createReaderModel()
            ->getWarehouseToStoreMapping();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return array
     */
    public function getStoreToWarehouseMapping()
    {
        return $this->getFactory()
            ->getConfig()
            ->getStoreToWarehouseMapping();
    }
}
