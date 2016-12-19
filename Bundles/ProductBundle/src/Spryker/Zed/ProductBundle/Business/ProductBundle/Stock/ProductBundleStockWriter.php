<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle\Stock;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\StockProductTransfer;
use Orm\Zed\ProductBundle\Persistence\Base\SpyProductBundle;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\ProductBundle\Business\ProductBundle\Availability\ProductBundleAvailabilityHandler;
use Spryker\Zed\ProductBundle\Dependency\QueryContainer\ProductBundleToStockQueryContainerInterface;
use Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface;

class ProductBundleStockWriter
{

    /**
     * @var ProductBundleQueryContainerInterface
     */
    protected $productBundleQueryContainer;

    /**
     * @var \Spryker\Zed\ProductBundle\Dependency\QueryContainer\ProductBundleToStockQueryContainerInterface
     */
    protected $stockQueryContainer;

    /**
     * @var \Spryker\Zed\ProductBundle\Business\ProductBundle\Availability\ProductBundleAvailabilityHandler
     */
    protected $productBundleAvailabilityHandler;

    /**
     * @param \Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface $productBundleQueryContainer
     * @param \Spryker\Zed\ProductBundle\Dependency\QueryContainer\ProductBundleToStockQueryContainerInterface $stockQueryContainer
     * @param \Spryker\Zed\ProductBundle\Business\ProductBundle\Availability\ProductBundleAvailabilityHandler $productBundleAvailabilityHandler
     */
    public function __construct(
        ProductBundleQueryContainerInterface $productBundleQueryContainer,
        ProductBundleToStockQueryContainerInterface $stockQueryContainer,
        ProductBundleAvailabilityHandler $productBundleAvailabilityHandler
    ) {
        $this->productBundleQueryContainer = $productBundleQueryContainer;
        $this->stockQueryContainer = $stockQueryContainer;
        $this->productBundleAvailabilityHandler = $productBundleAvailabilityHandler;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function updateStock(ProductConcreteTransfer $productConcreteTransfer)
    {
        $productConcreteTransfer->requireSku()
            ->requireIdProductConcrete();

        $bundleProductEntity = $this->productBundleQueryContainer
            ->queryBundleProductBySku($productConcreteTransfer->getSku())
            ->findOne();

        if ($bundleProductEntity === null) {
            return $productConcreteTransfer;
        }

        $bundleItems = $this->productBundleQueryContainer
            ->queryBundleProduct($productConcreteTransfer->getIdProductConcrete())
            ->find();

        $bundleTotalStockPerWarehause = $this->calculateBundleStockPerWarehouse($bundleItems);

        $this->updateBundleStock($productConcreteTransfer, $bundleTotalStockPerWarehause);

        $this->productBundleAvailabilityHandler->updateBundleAvailability($productConcreteTransfer->getSku());

        return $productConcreteTransfer;

    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param array $bundleTotalStockPerWarehause
     *
     * @return void
     */
    protected function updateBundleStock(
        ProductConcreteTransfer $productConcreteTransfer,
        array $bundleTotalStockPerWarehause
    ) {

        foreach ($bundleTotalStockPerWarehause as $idStock => $bundleStock) {

            $stockEntity = $this->stockQueryContainer
                ->queryStockByProducts($productConcreteTransfer->getIdProductConcrete())
                ->filterByFkStock($idStock)
                ->findOneOrCreate();

            $stockEntity->setQuantity($bundleStock);
            $stockEntity->save();

            $stockTransfer = new StockProductTransfer();
            $stockTransfer->setSku($productConcreteTransfer->getSku());
            $stockTransfer->setStockType($stockEntity->getStock()->getName());
            $stockTransfer->fromArray($stockEntity->toArray(), true);

            $productConcreteTransfer->addStock($stockTransfer);

        }
    }

    /**
     * @param ObjectCollection|SpyProductBundle[] $bundleItems
     *
     * @return array
     */
    protected function calculateBundleStockPerWarehouse(ObjectCollection $bundleItems)
    {
        $bundledItemStock = [];
        $bundledItemQuantity = [];
        foreach ($bundleItems as $bundleItemEntity) {
            $bundledProductEntity = $bundleItemEntity->getSpyProductRelatedByFkBundledProduct();

            $bundledItemQuantity[$bundledProductEntity->getIdProduct()] = $bundleItemEntity->getQuantity();

            $productStocks = $this->stockQueryContainer
                ->queryStockByProducts($bundledProductEntity->getIdProduct())
                ->find();

            foreach ($productStocks as $productStockEntity) {
                if (!isset($bundledItemStock[$productStockEntity->getFkStock()])) {
                    $bundledItemStock[$productStockEntity->getFkStock()] = [];
                }

                if (!isset($bundledItemStock[$productStockEntity->getFkStock()][$productStockEntity->getFkProduct()])) {
                    $bundledItemStock[$productStockEntity->getFkStock()][$productStockEntity->getFkProduct()] = [];
                }

                $bundledItemStock[$productStockEntity->getFkStock()][$productStockEntity->getFkProduct()] = $productStockEntity->getQuantity();
            }
        }

        return $this->groupBundleStockByWarehouse($bundledItemStock, $bundledItemQuantity);
    }

    /**
     * @param array $bundledItemStock
     * @param array $bundledItemQuantity
     *
     * @return array
     */
    protected function groupBundleStockByWarehouse(array $bundledItemStock, array $bundledItemQuantity)
    {
        $bundleTotalStockPerWarehause = [];
        foreach ($bundledItemStock as $idStock => $warehouseStock) {
            $bundleStock = 0;
            foreach ($warehouseStock as $idProduct => $productStockQuantity) {

                $quantity = $bundledItemQuantity[$idProduct];

                $itemStock = (int)floor($productStockQuantity / $quantity);

                if ($bundleStock > $itemStock || $bundleStock == 0) {
                    $bundleStock = $itemStock;
                }
            }

            $bundleTotalStockPerWarehause[$idStock] = $bundleStock;
        }
        return $bundleTotalStockPerWarehause;
    }
}
