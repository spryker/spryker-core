<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\AvailabilityStorage\Mapper;

use Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer;
use Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer;

class AvailabilityStorageMapper implements AvailabilityStorageMapperInterface
{
    protected const KEY = 'SpyAvailabilities';
    protected const KEY_SKU = 'sku';
    protected const KEY_IS_NEVER_OUT_OF_STOCK = 'is_never_out_of_stock';
    protected const KEY_QUANTITY = 'quantity';
    protected const KEY_ABSTRACT_SKU = 'abstract_sku';

    /**
     * @param array $availabilityStorageData
     * @param \Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer $productAbstractAvailabilityTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer
     */
    public function mapAvailabilityStorageDataToProductAbstractAvailabilityTransfer(
        array $availabilityStorageData,
        ProductAbstractAvailabilityTransfer $productAbstractAvailabilityTransfer
    ): ProductAbstractAvailabilityTransfer {
        $productAbstractAvailabilityTransfer->setAvailability($availabilityStorageData[static::KEY_QUANTITY]);
        $productAbstractAvailabilityTransfer->setSku($availabilityStorageData[static::KEY_ABSTRACT_SKU]);

        return $this->fillProductConcreteAvailabilitiesFromSpyAvailabilities(
            $productAbstractAvailabilityTransfer,
            $availabilityStorageData[static::KEY]
        );
    }

    /**
     * @param array $spyAvailabilityItem
     * @param \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer $productConcreteAvailabilityTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer
     */
    protected function mapSpyAvailabilityItemToProductConcreteAvailabilityTransfer(
        array $spyAvailabilityItem,
        ProductConcreteAvailabilityTransfer $productConcreteAvailabilityTransfer
    ): ProductConcreteAvailabilityTransfer {
        return $productConcreteAvailabilityTransfer->fromArray($spyAvailabilityItem, true)
            ->setSku($spyAvailabilityItem[static::KEY_SKU])
            ->setAvailability($spyAvailabilityItem[static::KEY_QUANTITY])
            ->setIsNeverOutOfStock(!empty($spyAvailabilityItem[static::KEY_IS_NEVER_OUT_OF_STOCK]));
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer $productAbstractAvailabilityTransfer
     * @param array $storageAvailabilityItems
     *
     * @return \Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer
     */
    protected function fillProductConcreteAvailabilitiesFromSpyAvailabilities(
        ProductAbstractAvailabilityTransfer $productAbstractAvailabilityTransfer,
        array $storageAvailabilityItems
    ): ProductAbstractAvailabilityTransfer {
        foreach ($storageAvailabilityItems as $storageAvailabilityItem) {
            $productConcreteAvailabilityTransfer = $this->mapSpyAvailabilityItemToProductConcreteAvailabilityTransfer(
                $storageAvailabilityItem,
                new ProductConcreteAvailabilityTransfer()
            );

            $productAbstractAvailabilityTransfer->addProductConcreteAvailability($productConcreteAvailabilityTransfer);
        }

        return $productAbstractAvailabilityTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer $productAbstractAvailabilityTransfer
     *
     * @return bool
     */
    protected function isProductAbstractAvailable(ProductAbstractAvailabilityTransfer $productAbstractAvailabilityTransfer): bool
    {
        return $productAbstractAvailabilityTransfer->getAvailability() !== null
            && $productAbstractAvailabilityTransfer->getAvailability()->greaterThan(0);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer $productConcreteAvailabilityTransfer
     *
     * @return bool
     */
    protected function isProductConcreteAvailable(
        ProductConcreteAvailabilityTransfer $productConcreteAvailabilityTransfer
    ): bool {
        return $productConcreteAvailabilityTransfer->getIsNeverOutOfStock()
            || ($productConcreteAvailabilityTransfer->getAvailability() !== null
                && $productConcreteAvailabilityTransfer->getAvailability()->greaterThan(0)
            );
    }
}
