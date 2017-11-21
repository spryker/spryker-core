<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle\Availability\PreCheck;

use ArrayObject;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToAvailabilityInterface;
use Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface;

class BasePreCheck
{
    /**
     * @var \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToAvailabilityInterface
     */
    protected $availabilityFacade;

    /**
     * @var \Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface
     */
    protected $productBundleQueryContainer;

    /**
     * @param \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToAvailabilityInterface $availabilityFacade
     * @param \Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface $productBundleQueryContainer
     */
    public function __construct(
        ProductBundleToAvailabilityInterface $availabilityFacade,
        ProductBundleQueryContainerInterface $productBundleQueryContainer
    ) {
        $this->availabilityFacade = $availabilityFacade;
        $this->productBundleQueryContainer = $productBundleQueryContainer;
    }

    /**
     * @param string $sku
     *
     * @return mixed|mixed[]|\Orm\Zed\ProductBundle\Persistence\SpyProductBundle[]|\Propel\Runtime\ActiveRecord\ActiveRecordInterface[]|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function findBundledProducts($sku)
    {
        return $this->productBundleQueryContainer
            ->queryBundleProductBySku($sku)
            ->find();
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $items
     * @param mixed|mixed[]|\Orm\Zed\ProductBundle\Persistence\SpyProductBundle[]|\Propel\Runtime\ActiveRecord\ActiveRecordInterface[]|\Propel\Runtime\Collection\ObjectCollection $bundledProducts
     * @param int $itemQuantity
     *
     * @return bool
     */
    protected function isAllBundleItemsAvailable(ArrayObject $items, ObjectCollection $bundledProducts, $itemQuantity)
    {
        foreach ($bundledProducts as $productBundleEntity) {
            $bundledProductConcreteEntity = $productBundleEntity->getSpyProductRelatedByFkBundledProduct();

            $sku = $bundledProductConcreteEntity->getSku();
            $totalBundledItemQuantity = $productBundleEntity->getQuantity() * $itemQuantity;
            if (!$this->checkIfItemIsSellable($items, $sku, $totalBundledItemQuantity)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $items
     * @param string $sku
     * @param int $itemQuantity
     *
     * @return bool
     */
    protected function checkIfItemIsSellable(ArrayObject $items, $sku, $itemQuantity = 0)
    {
        $currentItemQuantity = $this->getAccumulatedItemQuantityForGivenSku($items, $sku);
        $currentItemQuantity += $itemQuantity;

        return $this->availabilityFacade->isProductSellable($sku, $currentItemQuantity);
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $items
     * @param string $sku
     *
     * @return int
     */
    protected function getAccumulatedItemQuantityForGivenSku(ArrayObject $items, $sku)
    {
        $quantity = 0;
        foreach ($items as $itemTransfer) {
            if ($itemTransfer->getSku() !== $sku) {
                continue;
            }
            $quantity += $itemTransfer->getQuantity();
        }

        return $quantity;
    }
}
