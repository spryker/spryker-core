<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle\Availability\PreCheck;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\DecimalObject\Decimal;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToAvailabilityInterface;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToStoreFacadeInterface;
use Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface;
use Spryker\Zed\ProductBundle\ProductBundleConfig;

class BasePreCheck
{
    protected const ERROR_BUNDLE_ITEM_UNAVAILABLE_TRANSLATION_KEY = 'product_bundle.unavailable';
    protected const ERROR_BUNDLE_ITEM_UNAVAILABLE_PARAMETER_BUNDLE_SKU = '%bundleSku%';
    protected const ERROR_BUNDLE_ITEM_UNAVAILABLE_PARAMETER_PRODUCT_SKU = '%productSku%';

    /**
     * @var \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToAvailabilityInterface
     */
    protected $availabilityFacade;

    /**
     * @var \Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface
     */
    protected $productBundleQueryContainer;

    /**
     * @var \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\ProductBundle\ProductBundleConfig
     */
    protected $productBundleConfig;

    /**
     * @param \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToAvailabilityInterface $availabilityFacade
     * @param \Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface $productBundleQueryContainer
     * @param \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\ProductBundle\ProductBundleConfig $productBundleConfig
     */
    public function __construct(
        ProductBundleToAvailabilityInterface $availabilityFacade,
        ProductBundleQueryContainerInterface $productBundleQueryContainer,
        ProductBundleToStoreFacadeInterface $storeFacade,
        ProductBundleConfig $productBundleConfig
    ) {
        $this->availabilityFacade = $availabilityFacade;
        $this->productBundleQueryContainer = $productBundleQueryContainer;
        $this->storeFacade = $storeFacade;
        $this->productBundleConfig = $productBundleConfig;
    }

    /**
     * @param string $sku
     *
     * @return \Orm\Zed\ProductBundle\Persistence\SpyProductBundle[]
     */
    protected function findBundledProducts(string $sku): array
    {
        return $this->productBundleQueryContainer
            ->queryBundleProductBySku($sku)
            ->find()
            ->getData();
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     * @param \Orm\Zed\ProductBundle\Persistence\SpyProductBundle[] $bundledProducts
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return array
     */
    protected function getUnavailableBundleItems(
        ArrayObject $itemTransfers,
        array $bundledProducts,
        ItemTransfer $itemTransfer,
        StoreTransfer $storeTransfer
    ) {
        $unavailableBundleItems = [];

        foreach ($bundledProducts as $productBundleEntity) {
            $bundledProductConcreteEntity = $productBundleEntity->getSpyProductRelatedByFkBundledProduct();

            $sku = $bundledProductConcreteEntity->getSku();
            $totalBundledItemQuantity = $productBundleEntity->getQuantity() * $itemTransfer->getQuantity();
            if ($this->checkIfItemIsSellable($itemTransfers, $sku, $storeTransfer, new Decimal($totalBundledItemQuantity)) && $bundledProductConcreteEntity->getIsActive()) {
                continue;
            }
            $unavailableBundleItems[] = [
                static::ERROR_BUNDLE_ITEM_UNAVAILABLE_PARAMETER_BUNDLE_SKU => $itemTransfer->getSku(),
                static::ERROR_BUNDLE_ITEM_UNAVAILABLE_PARAMETER_PRODUCT_SKU => $sku,
            ];
        }

        return $unavailableBundleItems;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemsTransfers
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Spryker\DecimalObject\Decimal|null $itemQuantity
     *
     * @return bool
     */
    protected function checkIfItemIsSellable(
        iterable $itemsTransfers,
        string $sku,
        StoreTransfer $storeTransfer,
        ?Decimal $itemQuantity = null
    ): bool {
        if ($itemQuantity === null) {
            $itemQuantity = new Decimal(0);
        }

        $currentItemQuantity = $this->getAccumulatedItemQuantityForGivenSku($itemsTransfers, $sku);
        $currentItemQuantity = $currentItemQuantity->add($itemQuantity);

        return $this->availabilityFacade->isProductSellableForStore($sku, $currentItemQuantity, $storeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     * @param string $sku
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    protected function getAccumulatedItemQuantityForGivenSku(iterable $itemTransfers, string $sku): Decimal
    {
        $quantity = new Decimal(0);
        foreach ($itemTransfers as $itemTransfer) {
            if ($itemTransfer->getSku() !== $sku) {
                continue;
            }
            $quantity = $quantity->add($itemTransfer->getQuantity());
        }

        return $quantity;
    }
}
