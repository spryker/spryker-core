<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle\Availability;

use Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\ProductBundle\Persistence\SpyProductBundle;
use Spryker\DecimalObject\Decimal;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToAvailabilityFacadeInterface;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToStockFacadeInterface;
use Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface;

class ProductBundleAvailabilityHandler implements ProductBundleAvailabilityHandlerInterface
{
    protected const DIVISION_SCALE = 10;

    /**
     * @var \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToAvailabilityFacadeInterface
     */
    protected $availabilityFacade;

    /**
     * @var \Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface
     */
    protected $productBundleQueryContainer;

    /**
     * @var array
     */
    protected static $bundleItemEntityCache = [];

    /**
     * @var array
     */
    protected static $bundledItemEntityCache = [];

    /**
     * @var \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToStockFacadeInterface
     */
    protected $stockFacade;

    /**
     * @param \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToAvailabilityFacadeInterface $availabilityFacade
     * @param \Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface $productBundleQueryContainer
     * @param \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToStockFacadeInterface $stockFacade
     */
    public function __construct(
        ProductBundleToAvailabilityFacadeInterface $availabilityFacade,
        ProductBundleQueryContainerInterface $productBundleQueryContainer,
        ProductBundleToStockFacadeInterface $stockFacade
    ) {
        $this->availabilityFacade = $availabilityFacade;
        $this->productBundleQueryContainer = $productBundleQueryContainer;
        $this->stockFacade = $stockFacade;
    }

    /**
     * @param string $bundledProductSku
     *
     * @return void
     */
    public function updateAffectedBundlesAvailability($bundledProductSku)
    {
        $bundleProducts = $this->getBundlesUsingProductBySku($bundledProductSku);

        foreach ($bundleProducts as $productBundleEntity) {
            $bundleItems = $this->getBundleItemsByIdProduct($productBundleEntity->getFkProduct());

            $bundleProductSku = $productBundleEntity->getSpyProductRelatedByFkProduct()
                ->getSku();

            $this->updateBundleProductAvailability($bundleItems, $bundleProductSku);
        }
    }

    /**
     * @param string $bundleProductSku
     *
     * @return void
     */
    public function updateBundleAvailability($bundleProductSku)
    {
        $bundleProductEntity = $this->findBundleProductEntityBySku($bundleProductSku);
        if ($bundleProductEntity === null) {
            return;
        }

        $bundleItems = $this->getBundleItemsByIdProduct($bundleProductEntity->getFkProduct());
        $this->updateBundleProductAvailability($bundleItems, $bundleProductSku);
    }

    /**
     * @param string $bundleProductSku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return void
     */
    public function removeBundleAvailability($bundleProductSku, StoreTransfer $storeTransfer)
    {
        $this->availabilityFacade->saveProductAvailabilityForStore($bundleProductSku, new Decimal(0), $storeTransfer);
    }

    /**
     * @param int $idConcreteProduct
     *
     * @return \Orm\Zed\ProductBundle\Persistence\SpyProductBundle[]
     */
    protected function getBundleItemsByIdProduct($idConcreteProduct): array
    {
        if (!isset(static::$bundleItemEntityCache[$idConcreteProduct]) || count(static::$bundleItemEntityCache[$idConcreteProduct]) == 0) {
            static::$bundleItemEntityCache[$idConcreteProduct] = $this->productBundleQueryContainer
                ->queryBundleProduct($idConcreteProduct)
                ->find()
                ->getData();
        }

        return static::$bundleItemEntityCache[$idConcreteProduct];
    }

    /**
     * @param string $bundledProductSku
     *
     * @return \Orm\Zed\ProductBundle\Persistence\SpyProductBundle[]|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function getBundlesUsingProductBySku($bundledProductSku)
    {
        if (!isset(static::$bundledItemEntityCache[$bundledProductSku]) || count(static::$bundledItemEntityCache[$bundledProductSku]) == 0) {
            static::$bundledItemEntityCache[$bundledProductSku] = $this->productBundleQueryContainer
                ->queryBundledProductBySku($bundledProductSku)
                ->find();
        }

        return static::$bundledItemEntityCache[$bundledProductSku];
    }

    /**
     * @param \Orm\Zed\ProductBundle\Persistence\SpyProductBundle[] $bundleItems
     * @param string $bundleProductSku
     *
     * @return void
     */
    protected function updateBundleProductAvailability(array $bundleItems, string $bundleProductSku): void
    {
        $storeTransfers = $this->stockFacade->getStoresWhereProductStockIsDefined($bundleProductSku);
        if ($storeTransfers === []) {
            $this->updateBundleProductAvailabilityForProductWithNotDefinedStock($bundleProductSku);

            return;
        }

        foreach ($storeTransfers as $storeTransfer) {
            $bundleAvailabilityQuantity = $this->calculateBundleQuantity($bundleItems, $storeTransfer);

            $this->availabilityFacade->saveProductAvailabilityForStore(
                $bundleProductSku,
                $bundleAvailabilityQuantity,
                $storeTransfer
            );
        }
    }

    /**
     * @param string $bundleProductSku
     *
     * @return void
     */
    protected function updateBundleProductAvailabilityForProductWithNotDefinedStock(string $bundleProductSku): void
    {
        $storeTransfers = $this->availabilityFacade->getStoresWhereProductAvailabilityIsDefined($bundleProductSku);
        foreach ($storeTransfers as $storeTransfer) {
            $this->availabilityFacade->saveProductAvailabilityForStore(
                $bundleProductSku,
                new Decimal(0),
                $storeTransfer
            );
        }
    }

    /**
     * @param string $bundleProductSku
     *
     * @return \Orm\Zed\ProductBundle\Persistence\SpyProductBundle|null
     */
    protected function findBundleProductEntityBySku($bundleProductSku)
    {
        return $this->productBundleQueryContainer
            ->queryBundleProductBySku($bundleProductSku)
            ->findOne();
    }

    /**
     * @param string $bundledItemSku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer|null
     */
    protected function findBundledItemAvailabilityBySku(
        string $bundledItemSku,
        StoreTransfer $storeTransfer
    ): ?ProductConcreteAvailabilityTransfer {
        return $this->availabilityFacade
            ->findOrCreateProductConcreteAvailabilityBySkuForStore($bundledItemSku, $storeTransfer);
    }

    /**
     * @param \Orm\Zed\ProductBundle\Persistence\SpyProductBundle[] $bundleItems
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    protected function calculateBundleQuantity(array $bundleItems, StoreTransfer $storeTransfer): Decimal
    {
        $bundleAvailabilityQuantity = new Decimal(0);
        foreach ($bundleItems as $bundleItemEntity) {
            $bundledItemSku = $bundleItemEntity->getSpyProductRelatedByFkBundledProduct()->getSku();

            $bundledProductAvailabilityTransfer = $this->findBundledItemAvailabilityBySku(
                $bundledItemSku,
                $storeTransfer
            );

            if ($this->skipBundledItem($bundledProductAvailabilityTransfer)) {
                continue;
            }

            if ($this->isBundledItemUnavailable($bundledProductAvailabilityTransfer)) {
                return new Decimal(0);
            }

            $bundleAvailabilityQuantity = $this->calculateBundledItemQuantity(
                $bundledProductAvailabilityTransfer,
                $bundleItemEntity,
                $bundleAvailabilityQuantity
            );
        }

        return $bundleAvailabilityQuantity;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer|null $bundledProductAvailabilityTransfer
     *
     * @return bool
     */
    protected function isBundledItemUnavailable(?ProductConcreteAvailabilityTransfer $bundledProductAvailabilityTransfer)
    {
        if (!$bundledProductAvailabilityTransfer) {
            return false;
        }

        return $bundledProductAvailabilityTransfer->getAvailability()->isZero() && !$bundledProductAvailabilityTransfer->getIsNeverOutOfStock();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer|null $bundledProductAvailabilityTransfer
     *
     * @return bool
     */
    protected function skipBundledItem(?ProductConcreteAvailabilityTransfer $bundledProductAvailabilityTransfer): bool
    {
        if ($bundledProductAvailabilityTransfer === null) {
            return false;
        }

        return $bundledProductAvailabilityTransfer->getIsNeverOutOfStock() ?? false;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer|null $bundledProductAvailabilityTransfer
     * @param \Orm\Zed\ProductBundle\Persistence\SpyProductBundle $bundleItemEntity
     * @param \Spryker\DecimalObject\Decimal $bundleAvailabilityQuantity
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    protected function calculateBundledItemQuantity(
        ?ProductConcreteAvailabilityTransfer $bundledProductAvailabilityTransfer,
        SpyProductBundle $bundleItemEntity,
        Decimal $bundleAvailabilityQuantity
    ): Decimal {
        if (!$bundledProductAvailabilityTransfer) {
            return new Decimal(0);
        }

        $bundledItemQuantity = $bundledProductAvailabilityTransfer->getAvailability()
            ->divide($bundleItemEntity->getQuantity(), static::DIVISION_SCALE)
            ->floor();

        if ($this->isMaxQuantity($bundleAvailabilityQuantity, $bundledItemQuantity)) {
            return $bundledItemQuantity;
        }

        return $bundleAvailabilityQuantity;
    }

    /**
     * @param \Spryker\DecimalObject\Decimal $bundleAvailabilityQuantity
     * @param \Spryker\DecimalObject\Decimal $bundledItemQuantity
     *
     * @return bool
     */
    protected function isMaxQuantity(Decimal $bundleAvailabilityQuantity, Decimal $bundledItemQuantity): bool
    {
        return $bundleAvailabilityQuantity->greatherThanOrEquals($bundledItemQuantity) || $bundleAvailabilityQuantity->equals(0);
    }
}
