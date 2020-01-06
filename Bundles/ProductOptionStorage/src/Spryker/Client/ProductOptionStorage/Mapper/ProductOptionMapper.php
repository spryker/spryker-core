<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOptionStorage\Mapper;

use Generated\Shared\Transfer\ProductAbstractOptionStorageTransfer;

class ProductOptionMapper implements ProductOptionMapperInterface
{
    /**
     * @param array $productAbstractOptionStorageData
     * @param \Generated\Shared\Transfer\ProductAbstractOptionStorageTransfer $productAbstractOptionStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractOptionStorageTransfer
     */
    public function mapProductAbstractOptionStorageDataItemToProductAbstractOptionStorageTransfer(
        array $productAbstractOptionStorageData,
        ProductAbstractOptionStorageTransfer $productAbstractOptionStorageTransfer
    ): ProductAbstractOptionStorageTransfer {
        return $productAbstractOptionStorageTransfer->fromArray($productAbstractOptionStorageData, true);
    }

    /**
     * @param array $productOptionStorageDataItems
     * @param \Generated\Shared\Transfer\ProductAbstractOptionStorageTransfer[] $productAbstractOptionStorageTransfers
     *
     * @return \Generated\Shared\Transfer\ProductAbstractOptionStorageTransfer[]
     */
    public function mapProductAbstractOptionStorageDataItemsToProductAbstractOptionStorageTransfers(
        array $productOptionStorageDataItems,
        array $productAbstractOptionStorageTransfers = []
    ): array {
        foreach ($productOptionStorageDataItems as $productOptionStorageDataItem) {
            $productAbstractOptionStorageTransfers[] =
                $this->mapProductAbstractOptionStorageDataItemToProductAbstractOptionStorageTransfer(
                    $productOptionStorageDataItem,
                    new ProductAbstractOptionStorageTransfer()
                );
        }

        return $productAbstractOptionStorageTransfers;
    }
}
