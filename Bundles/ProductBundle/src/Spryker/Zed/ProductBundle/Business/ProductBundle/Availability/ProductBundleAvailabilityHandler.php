<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle\Availability;

use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToAvailabilityInterface;
use Spryker\Zed\ProductBundle\Dependency\QueryContainer\ProductBundleToAvailabilityQueryContainerInterface;
use Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface;

class ProductBundleAvailabilityHandler
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
    public function updateBundleAvailability($bundledProductSku)
    {
        $bundledProducts = $this->getBundleItemsBySku($bundledProductSku);

        foreach ($bundledProducts as $bundledProductEntity) {

            $bundleItems = $this->getBundleItemsByIdProduct($bundledProductEntity->getFkProduct());

            $maxQty = 0;
            $maxQtyAvailability = 0;
            foreach ($bundleItems as $bundleItemEntity) {
                $bundledItemQuantity = $bundleItemEntity->getQuantity();

                if ($bundledItemQuantity > $maxQty) {
                    $maxQty = $bundledItemQuantity;

                    $bundledItemSku = $bundleItemEntity->getSpyProductRelatedByFkBundledProduct()
                        ->getSku();

                    $bundledProductAvailability = $this->availabilityQueryContainer
                        ->querySpyAvailabilityBySku($bundledItemSku)
                        ->findOne();

                    $maxQtyAvailability = $bundledProductAvailability->getQuantity();
                }
            }

            $bundleAvailabilityQuantity = floor($maxQtyAvailability / $maxQty);

            $bundleItemSku = $bundledProductEntity
                ->getSpyProductRelatedByFkProduct()
                ->getSku();

            $bundleProductAvailability = $this->availabilityQueryContainer
                ->querySpyAvailabilityBySku($bundleItemSku)
                ->findOne();

            if ($bundleProductAvailability->getQuantity() != $bundleAvailabilityQuantity) {
                $bundleProductAvailability
                    ->setQuantity($bundleAvailabilityQuantity)
                    ->save();

                $this->updateAbstractAvailabilityQuantity($bundleProductAvailability->getFkAvailabilityAbstract());
                $this->availabilityFacade->touchAvailabilityAbstract($bundleProductAvailability->getFkAvailabilityAbstract());
            }
        }
    }

    /**
     * @param int $idAvailabilityAbstract
     *
     * @return void
     */
    protected function updateAbstractAvailabilityQuantity($idAvailabilityAbstract)
    {
        $availabilityAbstractEntity = $this->availabilityQueryContainer
            ->queryAvailabilityAbstractByIdAvailabilityAbstract($idAvailabilityAbstract)
            ->findOne();

        $sumQuantity = (int)$this->availabilityQueryContainer
            ->querySumQuantityOfAvailabilityAbstract($idAvailabilityAbstract)
            ->findOne();

        $availabilityAbstractEntity->setQuantity($sumQuantity);
        $availabilityAbstractEntity->save();
    }

    /**
     * @param int $idConcreteProduct
     *
     * @return \Orm\Zed\ProductBundle\Persistence\SpyProductBundle[]|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function getBundleItemsByIdProduct($idConcreteProduct)
    {
        if (!isset(static::$bundleItemEntityCache[$idConcreteProduct])) {
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
    protected function getBundleItemsBySku($bundledProductSku)
    {
        if (!isset(static::$bundledItemEntityCache[$bundledProductSku])) {
            static::$bundledItemEntityCache[$bundledProductSku] = $this->productBundleQueryContainer
                ->queryBundledProductBySku($bundledProductSku)
                ->find();
        }

        return static::$bundledItemEntityCache[$bundledProductSku];
    }

}
