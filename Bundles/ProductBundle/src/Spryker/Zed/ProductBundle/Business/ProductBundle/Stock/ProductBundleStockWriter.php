<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle\Stock;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\StockProductTransfer;
use Orm\Zed\Stock\Persistence\SpyStockProduct;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\DecimalObject\Decimal;
use Spryker\Zed\ProductBundle\Business\ProductBundle\Availability\ProductBundleAvailabilityHandlerInterface;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToStoreFacadeInterface;
use Spryker\Zed\ProductBundle\Dependency\QueryContainer\ProductBundleToStockQueryContainerInterface;
use Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface;
use Throwable;

class ProductBundleStockWriter implements ProductBundleStockWriterInterface
{
    /**
     * @var string
     */
    public const IS_NEVER_OUT_OF_STOCK = 'is_never_out_of_stock';

    /**
     * @var string
     */
    public const QUANTITY = 'quantity';

    /**
     * @var int
     */
    protected const DIVISION_SCALE = 10;

    /**
     * @var \Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface
     */
    protected $productBundleQueryContainer;

    /**
     * @var \Spryker\Zed\ProductBundle\Dependency\QueryContainer\ProductBundleToStockQueryContainerInterface
     */
    protected $stockQueryContainer;

    /**
     * @var \Spryker\Zed\ProductBundle\Business\ProductBundle\Availability\ProductBundleAvailabilityHandlerInterface
     */
    protected $productBundleAvailabilityHandler;

    /**
     * @var \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface $productBundleQueryContainer
     * @param \Spryker\Zed\ProductBundle\Dependency\QueryContainer\ProductBundleToStockQueryContainerInterface $stockQueryContainer
     * @param \Spryker\Zed\ProductBundle\Business\ProductBundle\Availability\ProductBundleAvailabilityHandlerInterface $productBundleAvailabilityHandler
     * @param \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        ProductBundleQueryContainerInterface $productBundleQueryContainer,
        ProductBundleToStockQueryContainerInterface $stockQueryContainer,
        ProductBundleAvailabilityHandlerInterface $productBundleAvailabilityHandler,
        ProductBundleToStoreFacadeInterface $storeFacade
    ) {
        $this->productBundleQueryContainer = $productBundleQueryContainer;
        $this->stockQueryContainer = $stockQueryContainer;
        $this->productBundleAvailabilityHandler = $productBundleAvailabilityHandler;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @throws \Exception
     * @throws \Throwable
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function updateStock(ProductConcreteTransfer $productConcreteTransfer)
    {
        $productConcreteTransfer->requireSku()
            ->requireIdProductConcrete();

        $bundleProductEntity = $this->findProductBundleBySku($productConcreteTransfer->getSku());

        if ($bundleProductEntity === null) {
            $this->removeBundleStock($productConcreteTransfer);

            return $productConcreteTransfer;
        }

        $bundleItems = $this->findBundledItemsByIdBundleProduct($productConcreteTransfer->getIdProductConcrete());

        $bundleTotalStockPerWarehouse = $this->calculateBundleStockPerWarehouse($bundleItems);

        try {
            $this->productBundleQueryContainer->getConnection()->beginTransaction();

            $this->updateBundleStock($productConcreteTransfer, $bundleTotalStockPerWarehouse);
            $this->productBundleQueryContainer->getConnection()->commit();
        } catch (Throwable $exception) {
            $this->productBundleQueryContainer->getConnection()->rollBack();

            throw $exception;
        }

        $this->productBundleAvailabilityHandler->updateBundleAvailability($productConcreteTransfer->getSku());

        return $productConcreteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param array<int, array<string, mixed>> $bundleTotalStockPerWarehouse
     *
     * @return void
     */
    protected function updateBundleStock(
        ProductConcreteTransfer $productConcreteTransfer,
        array $bundleTotalStockPerWarehouse
    ) {
        $bundleTotalStockPerWarehouse = $this->removeBundleStockFromWarehousesWithoutBundledItems(
            $productConcreteTransfer,
            $bundleTotalStockPerWarehouse,
        );

        foreach ($bundleTotalStockPerWarehouse as $idStock => $bundleStock) {
            $stockEntity = $this->findOrCreateProductStockEntity($productConcreteTransfer, $idStock);

            $stockEntity->setQuantity($bundleStock[static::QUANTITY]);
            $stockEntity->setIsNeverOutOfStock($bundleStock[static::IS_NEVER_OUT_OF_STOCK]);
            $stockEntity->save();

            $stockTransfer = $this->mapStockTransfer($productConcreteTransfer, $stockEntity);

            $productConcreteTransfer->addStock($stockTransfer);
        }
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\ProductBundle\Persistence\SpyProductBundle> $bundleItems
     *
     * @return array<int, array<string, mixed>>
     */
    protected function calculateBundleStockPerWarehouse(ObjectCollection $bundleItems)
    {
        $bundledItemStock = [];
        $bundledItemQuantity = [];
        foreach ($bundleItems as $bundleItemEntity) {
            $bundledProductEntity = $bundleItemEntity->getSpyProductRelatedByFkBundledProduct();

            $bundledItemQuantity[$bundledProductEntity->getIdProduct()] = $bundleItemEntity->getQuantity();

            $bundledItemStock = $this->getStockGroupedByBundledItem($bundledProductEntity->getIdProduct(), $bundledItemStock);
        }

        return $this->groupBundleStockByWarehouse($bundledItemStock, $bundledItemQuantity);
    }

    /**
     * @param int $idBundledProduct
     * @param array<int, array<int, array<string, mixed>>> $bundledItemStock
     *
     * @return array<int, array<int, array<string, mixed>>>
     */
    protected function getStockGroupedByBundledItem($idBundledProduct, array $bundledItemStock)
    {
        $productStocks = $this->findProductStocks($idBundledProduct);

        foreach ($productStocks as $productStockEntity) {
            if (!isset($bundledItemStock[$productStockEntity->getFkStock()])) {
                $bundledItemStock[$productStockEntity->getFkStock()] = [];
            }

            if (!isset($bundledItemStock[$productStockEntity->getFkStock()][$productStockEntity->getFkProduct()])) {
                $bundledItemStock[$productStockEntity->getFkStock()][$productStockEntity->getFkProduct()] = [];
            }

            $bundledItemStock[$productStockEntity->getFkStock()][$productStockEntity->getFkProduct()] = [
                static::QUANTITY => $productStockEntity->getQuantity(),
                static::IS_NEVER_OUT_OF_STOCK => $productStockEntity->getIsNeverOutOfStock(),
            ];
        }

        return $bundledItemStock;
    }

    /**
     * @param array<int, array<int, array<string, mixed>>> $bundledItemStock
     * @param array<int, int> $bundleItemQuantitiesIndexedByIdProduct
     *
     * @return array<int, array<string, mixed>>
     */
    protected function groupBundleStockByWarehouse(array $bundledItemStock, array $bundleItemQuantitiesIndexedByIdProduct): array
    {
        $bundleTotalStockPerWarehouse = [];

        foreach ($bundledItemStock as $idStock => $stockIndexedByIdProduct) {
            $productBundleItemQuantities = $this->calculateProductBundleItemQuantitiesForStock(
                $bundleItemQuantitiesIndexedByIdProduct,
                $stockIndexedByIdProduct,
            );
            $quantity = $productBundleItemQuantities ? min($productBundleItemQuantities) : 0;

            $bundleTotalStockPerWarehouse[$idStock] = [
                static::QUANTITY => new Decimal($quantity),
                static::IS_NEVER_OUT_OF_STOCK => count($productBundleItemQuantities) === 0,
            ];
        }

        return $bundleTotalStockPerWarehouse;
    }

    /**
     * @param array<int, int> $bundleItemQuantitiesIndexedByIdProduct
     * @param array<int, array<string, mixed>> $stockIndexedByIdProduct
     *
     * @return list<int>
     */
    protected function calculateProductBundleItemQuantitiesForStock(array $bundleItemQuantitiesIndexedByIdProduct, array $stockIndexedByIdProduct): array
    {
        $productBundleItemQuantities = [];

        foreach ($bundleItemQuantitiesIndexedByIdProduct as $idProduct => $bundleQuantity) {
            if (!isset($stockIndexedByIdProduct[$idProduct])) {
                $productBundleItemQuantities[] = 0;

                continue;
            }

            if ($stockIndexedByIdProduct[$idProduct][static::IS_NEVER_OUT_OF_STOCK]) {
                continue;
            }

            $productBundleItemQuantities[] = (new Decimal($stockIndexedByIdProduct[$idProduct][static::QUANTITY]))
                ->divide($bundleQuantity, static::DIVISION_SCALE)
                ->floor()
                ->toInt();
        }

        return $productBundleItemQuantities;
    }

    /**
     * @param string $sku
     *
     * @return \Orm\Zed\ProductBundle\Persistence\SpyProductBundle|null
     */
    protected function findProductBundleBySku($sku)
    {
        return $this->productBundleQueryContainer
            ->queryBundleProductBySku($sku)
            ->findOne();
    }

    /**
     * @param int $idProductConcrete
     *
     * @return \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\ProductBundle\Persistence\SpyProductBundle>
     */
    protected function findBundledItemsByIdBundleProduct($idProductConcrete)
    {
        return $this->productBundleQueryContainer
            ->queryBundleProduct($idProductConcrete)
            ->find();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param int $idStock
     *
     * @return \Orm\Zed\Stock\Persistence\SpyStockProduct
     */
    protected function findOrCreateProductStockEntity(ProductConcreteTransfer $productConcreteTransfer, $idStock)
    {
        return $this->stockQueryContainer
            ->queryStockByProducts($productConcreteTransfer->getIdProductConcrete())
            ->filterByFkStock($idStock)
            ->findOneOrCreate();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Orm\Zed\Stock\Persistence\SpyStockProduct $stockProductEntity
     *
     * @return \Generated\Shared\Transfer\StockProductTransfer
     */
    protected function mapStockTransfer(
        ProductConcreteTransfer $productConcreteTransfer,
        SpyStockProduct $stockProductEntity
    ) {
        $stockTransfer = new StockProductTransfer();
        $stockTransfer->setSku($productConcreteTransfer->getSku());
        $stockTransfer->setStockType($stockProductEntity->getStock()->getName());
        $stockTransfer->fromArray($stockProductEntity->toArray(), true);

        return $stockTransfer;
    }

    /**
     * @param int $idProduct
     *
     * @return \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\Stock\Persistence\SpyStockProduct>
     */
    protected function findProductStocks($idProduct)
    {
        return $this->stockQueryContainer
            ->queryStockByProducts($idProduct)
            ->find();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return void
     */
    protected function removeBundleStock(ProductConcreteTransfer $productConcreteTransfer)
    {
        foreach ($this->findProductStocks($productConcreteTransfer->getIdProductConcrete()) as $stockProductEntity) {
            $stockProductEntity->setQuantity(new Decimal(0));
            $stockProductEntity->setIsNeverOutOfStock(false);
            $stockProductEntity->save();

            $stockTransfer = $this->mapStockTransfer($productConcreteTransfer, $stockProductEntity);

            $productConcreteTransfer->addStock($stockTransfer);
        }

        $this->removeBundleStockFromSharedStores($productConcreteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param array<int, array<string, mixed>> $bundleTotalStockPerWarehouse
     *
     * @return array<int, array<string, mixed>>
     */
    protected function removeBundleStockFromWarehousesWithoutBundledItems(
        ProductConcreteTransfer $productConcreteTransfer,
        array $bundleTotalStockPerWarehouse
    ) {
        $productStock = $this->findProductStocks($productConcreteTransfer->getIdProductConcrete());

        foreach ($productStock as $productStockEntity) {
            if (isset($bundleTotalStockPerWarehouse[$productStockEntity->getFkStock()])) {
                continue;
            }

            $productStockEntity->setQuantity(new Decimal(0));
            $productStockEntity->setIsNeverOutOfStock(false);
            $productStockEntity->save();
        }

        return $bundleTotalStockPerWarehouse;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return void
     */
    protected function removeBundleStockFromSharedStores(ProductConcreteTransfer $productConcreteTransfer): void
    {
        foreach ($this->storeFacade->getAllStores() as $storeTransfer) {
            $this->productBundleAvailabilityHandler->removeBundleAvailability(
                $productConcreteTransfer->getSku(),
                $storeTransfer,
            );
        }
    }
}
