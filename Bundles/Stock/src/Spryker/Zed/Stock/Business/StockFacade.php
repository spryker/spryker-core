<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Stock\Business;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\StockProductTransfer;
use Generated\Shared\Transfer\StockResponseTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Generated\Shared\Transfer\TypeTransfer;
use Spryker\DecimalObject\Decimal;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Stock\Business\StockBusinessFactory getFactory()
 * @method \Spryker\Zed\Stock\Persistence\StockRepositoryInterface getRepository()
 * @method \Spryker\Zed\Stock\Persistence\StockEntityManagerInterface getEntityManager()
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
        return $this->getFactory()->createStockProductReader()->isNeverOutOfStock($sku);
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
        return $this->getFactory()->createStockProductReader()->isNeverOutOfStockForStore($sku, $storeTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $abstractSku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return bool
     */
    public function isProductAbstractNeverOutOfStockForStore(string $abstractSku, StoreTransfer $storeTransfer): bool
    {
        return $this->getFactory()
            ->createStockProductReader()
            ->isProductAbstractNeverOutOfStockForStore($abstractSku, $storeTransfer);
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
     * @param string $abstractSku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    public function calculateProductAbstractStockForStore(string $abstractSku, StoreTransfer $storeTransfer): Decimal
    {
        return $this->getFactory()
            ->createCalculatorModel()
            ->calculateProductAbstractStockForStore($abstractSku, $storeTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link createStock()} instead.
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
     * @param \Spryker\DecimalObject\Decimal $decrementBy
     *
     * @return void
     */
    public function decrementStockProduct($sku, $stockType, Decimal $decrementBy): void
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
     * @param \Spryker\DecimalObject\Decimal $incrementBy
     *
     * @return void
     */
    public function incrementStockProduct($sku, $stockType, Decimal $incrementBy): void
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
        return $this->getFactory()->createStockProductReader()->hasStockProduct($sku, $stockType);
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
            ->createStockProductReader()
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
        return $this->getFactory()->createStockReader()->getStockTypes();
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
            ->createStockProductReader()
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
            ->createStockProductReader()
            ->findStockProductsByIdProductForStore($idProductConcrete, $storeTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return string[]
     */
    public function getStockTypesForStore(StoreTransfer $storeTransfer)
    {
        return $this->getFactory()->createStockReader()->getStockTypesForStore($storeTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string[][]
     */
    public function getWarehouseToStoreMapping()
    {
        return $this->getFactory()
            ->createStockReader()
            ->getWarehouseToStoreMapping();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string[][]
     */
    public function getStoreToWarehouseMapping()
    {
        return $this->getFactory()
            ->createStockReader()
            ->getStoreToWarehouseMapping();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idStock
     *
     * @return \Generated\Shared\Transfer\StockTransfer|null
     */
    public function findStockById(int $idStock): ?StockTransfer
    {
        return $this->getRepository()->findStockById($idStock);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $stockName
     *
     * @return \Generated\Shared\Transfer\StockTransfer|null
     */
    public function findStockByName(string $stockName): ?StockTransfer
    {
        return $this->getRepository()->findStockByName($stockName);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StockTransfer $stockTransfer
     *
     * @throws \Spryker\Zed\Stock\Business\Exception\StockNotSavedException
     *
     * @return \Generated\Shared\Transfer\StockResponseTransfer
     */
    public function createStock(StockTransfer $stockTransfer): StockResponseTransfer
    {
        return $this->getFactory()
            ->createStockCreator()
            ->createStock($stockTransfer);
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
    public function updateStock(StockTransfer $stockTransfer): StockResponseTransfer
    {
        return $this->getFactory()
            ->createStockUpdater()
            ->updateStock($stockTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\StoreTransfer[]
     */
    public function getStoresWhereProductStockIsDefined(string $sku): array
    {
        return $this->getRepository()->getStoresWhereProductStockIsDefined($sku);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StockTransfer[]
     */
    public function getAvailableWarehousesForStore(StoreTransfer $storeTransfer): array
    {
        return $this->getFactory()
            ->createStockReader()
            ->getAvailableWarehousesForStore($storeTransfer);
    }
}
