<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\AvailabilityStorage\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer;
use Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer;

class AvailabilityStorageMapper implements AvailabilityStorageMapperInterface
{
    protected const KEY_SPY_AVAILABILITIES = 'SpyAvailabilities';
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

        $productConcreteAvailabilityTransfers = $this->mapProductConcreteAvailabilityDataToProductConcreteAvailabilityTransfers(
            $availabilityStorageData[static::KEY_SPY_AVAILABILITIES],
            new ArrayObject()
        );

        $productAbstractAvailabilityTransfer->setProductConcreteAvailabilities($productConcreteAvailabilityTransfers);

        return $productAbstractAvailabilityTransfer;
    }

    /**
     * @param array $productConcreteAvailabilityDataItems
     * @param \ArrayObject|\Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer[] $productConcreteAvailabilityTransfers
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer[]
     */
    protected function mapProductConcreteAvailabilityDataToProductConcreteAvailabilityTransfers(
        array $productConcreteAvailabilityDataItems,
        ArrayObject $productConcreteAvailabilityTransfers
    ): ArrayObject {
        foreach ($productConcreteAvailabilityDataItems as $productConcreteAvailabilityDataItem) {
            $productConcreteAvailabilityTransfer = (new ProductConcreteAvailabilityTransfer())
                ->fromArray($productConcreteAvailabilityDataItem, true)
                ->setSku($productConcreteAvailabilityDataItem[static::KEY_SKU])
                ->setAvailability($productConcreteAvailabilityDataItem[static::KEY_QUANTITY])
                ->setIsNeverOutOfStock((bool)$productConcreteAvailabilityDataItem[static::KEY_IS_NEVER_OUT_OF_STOCK]);

            $productConcreteAvailabilityTransfers->append($productConcreteAvailabilityTransfer);
        }

        return $productConcreteAvailabilityTransfers;
    }
}
