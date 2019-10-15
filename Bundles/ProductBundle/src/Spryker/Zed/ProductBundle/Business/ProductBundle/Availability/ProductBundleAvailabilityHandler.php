<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle\Availability;

use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Availability\Persistence\SpyAvailability;
use Orm\Zed\ProductBundle\Persistence\SpyProductBundle;
use Spryker\DecimalObject\Decimal;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToAvailabilityInterface;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToStockFacadeInterface;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToStoreFacadeInterface;
use Spryker\Zed\ProductBundle\Dependency\QueryContainer\ProductBundleToAvailabilityQueryContainerInterface;
use Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface;

class ProductBundleAvailabilityHandler implements ProductBundleAvailabilityHandlerInterface
{
    protected const DIVISION_SCALE = 10;

    /**
     * @var \Spryker\Zed\ProductBundle\Dependency\QueryContainer\ProductBundleToAvailabilityQueryContainerInterface
     */
    protected $availabilityQueryContainer;

    /**
     * @var \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToAvailabilityInterface
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
     * @var \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToStockFacadeInterface
     */
    protected $stockFacade;

    /**
     * @param \Spryker\Zed\ProductBundle\Dependency\QueryContainer\ProductBundleToAvailabilityQueryContainerInterface $availabilityQueryContainer
     * @param \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToAvailabilityInterface $availabilityFacade
     * @param \Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface $productBundleQueryContainer
     * @param \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToStockFacadeInterface $stockFacade
     */
    public function __construct(
        ProductBundleToAvailabilityQueryContainerInterface $availabilityQueryContainer,
        ProductBundleToAvailabilityInterface $availabilityFacade,
        ProductBundleQueryContainerInterface $productBundleQueryContainer,
        ProductBundleToStoreFacadeInterface $storeFacade,
        ProductBundleToStockFacadeInterface $stockFacade
    ) {
        $this->availabilityQueryContainer = $availabilityQueryContainer;
        $this->availabilityFacade = $availabilityFacade;
        $this->productBundleQueryContainer = $productBundleQueryContainer;
        $this->storeFacade = $storeFacade;
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
     * @param int $idStore
     *
     * @return \Orm\Zed\Availability\Persistence\SpyAvailability|null
     */
    protected function findBundledItemAvailabilityEntityBySku($bundledItemSku, $idStore)
    {
        return $this->availabilityQueryContainer
            ->querySpyAvailabilityBySku($bundledItemSku, $idStore)
            ->findOne();
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

            $bundledProductAvailabilityEntity = $this->findBundledItemAvailabilityEntityBySku(
                $bundledItemSku,
                $storeTransfer->getIdStore()
            );

            if ($this->skipBundledItem($bundledProductAvailabilityEntity)) {
                continue;
            }

            if ($this->isBundledItemUnavailable($bundledProductAvailabilityEntity)) {
                return new Decimal(0);
            }

            $bundleAvailabilityQuantity = $this->calculateBundledItemQuantity(
                $bundledProductAvailabilityEntity,
                $bundleItemEntity,
                $bundleAvailabilityQuantity
            );
        }

        return $bundleAvailabilityQuantity;
    }

    /**
     * @param \Orm\Zed\Availability\Persistence\SpyAvailability|null $bundledProductAvailabilityEntity
     *
     * @return bool
     */
    protected function isBundledItemUnavailable(?SpyAvailability $bundledProductAvailabilityEntity)
    {
        if (!$bundledProductAvailabilityEntity) {
            return false;
        }

        return (new Decimal($bundledProductAvailabilityEntity->getQuantity()))->isZero() && !$bundledProductAvailabilityEntity->getIsNeverOutOfStock();
    }

    /**
     * @param \Orm\Zed\Availability\Persistence\SpyAvailability|null $bundledProductAvailabilityEntity
     *
     * @return bool
     */
    protected function skipBundledItem(?SpyAvailability $bundledProductAvailabilityEntity)
    {
        if ($bundledProductAvailabilityEntity === null) {
            return false;
        }

        return $bundledProductAvailabilityEntity->getIsNeverOutOfStock();
    }

    /**
     * @param \Orm\Zed\Availability\Persistence\SpyAvailability|null $bundledProductAvailabilityEntity
     * @param \Orm\Zed\ProductBundle\Persistence\SpyProductBundle $bundleItemEntity
     * @param \Spryker\DecimalObject\Decimal $bundleAvailabilityQuantity
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    protected function calculateBundledItemQuantity(
        ?SpyAvailability $bundledProductAvailabilityEntity,
        SpyProductBundle $bundleItemEntity,
        Decimal $bundleAvailabilityQuantity
    ): Decimal {
        if (!$bundledProductAvailabilityEntity) {
            return new Decimal(0);
        }

        $bundledItemQuantity = (new Decimal($bundledProductAvailabilityEntity->getQuantity()))
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
