<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle\Availability;

use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToAvailabilityInterface;
use Spryker\Zed\ProductBundle\Dependency\QueryContainer\ProductBundleToAvailabilityQueryContainerInterface;
use Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface;

class ProductBundleAvailabilityHandler implements ProductBundleAvailabilityHandlerInterface
{

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
     * @param \Spryker\Zed\ProductBundle\Dependency\QueryContainer\ProductBundleToAvailabilityQueryContainerInterface $availabilityQueryContainer
     * @param \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToAvailabilityInterface $availabilityFacade
     * @param \Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface $productBundleQueryContainer
     */
    public function __construct(
        ProductBundleToAvailabilityQueryContainerInterface $availabilityQueryContainer,
        ProductBundleToAvailabilityInterface $availabilityFacade,
        ProductBundleQueryContainerInterface $productBundleQueryContainer
    ) {
        $this->availabilityQueryContainer = $availabilityQueryContainer;
        $this->availabilityFacade = $availabilityFacade;
        $this->productBundleQueryContainer = $productBundleQueryContainer;
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
     *
     * @return void
     */
    public function removeBundleAvailability($bundleProductSku)
    {
        $this->availabilityFacade->saveProductAvailability($bundleProductSku, 0);
    }

    /**
     * @param int $idConcreteProduct
     *
     * @return \Orm\Zed\ProductBundle\Persistence\SpyProductBundle[]|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function getBundleItemsByIdProduct($idConcreteProduct)
    {
        if (!isset(static::$bundleItemEntityCache[$idConcreteProduct]) || count(static::$bundleItemEntityCache[$idConcreteProduct]) == 0) {
            static::$bundleItemEntityCache[$idConcreteProduct] = $this->productBundleQueryContainer
                ->queryBundleProduct($idConcreteProduct)
                ->find();
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
     * @param array $bundleItems
     * @param string $bundleProductSku
     *
     * @return void
     */
    protected function updateBundleProductAvailability($bundleItems, $bundleProductSku)
    {
        $bundleAvailabilityQuantity = 0;
        foreach ($bundleItems as $bundleItemEntity) {

            $bundledItemSku = $bundleItemEntity->getSpyProductRelatedByFkBundledProduct()
                ->getSku();

            $bundledProductAvailabilityEntity = $this->findBundledItemAvailabilityEntityBySku($bundledItemSku);

            if ($bundledProductAvailabilityEntity === null || $bundledProductAvailabilityEntity->getIsNeverOutOfStock()) {
                continue;
            }

            $bundledItemQuantity = (int)floor($bundledProductAvailabilityEntity->getQuantity() / $bundleItemEntity->getQuantity());

            if ($bundleAvailabilityQuantity > $bundledItemQuantity || $bundleAvailabilityQuantity == 0) {
                $bundleAvailabilityQuantity = $bundledItemQuantity;
            }
        }

        $this->availabilityFacade->saveProductAvailability($bundleProductSku, $bundleAvailabilityQuantity);
    }

    /**
     * @param string $bundleProductSku
     *
     * @return \Orm\Zed\ProductBundle\Persistence\SpyProductBundle
     */
    protected function findBundleProductEntityBySku($bundleProductSku)
    {
        return $this->productBundleQueryContainer
            ->queryBundleProductBySku($bundleProductSku)
            ->findOne();
    }

    /**
     * @param string $bundledItemSku
     *
     * @return \Orm\Zed\Availability\Persistence\SpyAvailability
     */
    protected function findBundledItemAvailabilityEntityBySku($bundledItemSku)
    {
        return $this->availabilityQueryContainer
            ->querySpyAvailabilityBySku($bundledItemSku)
            ->findOne();
    }

}
