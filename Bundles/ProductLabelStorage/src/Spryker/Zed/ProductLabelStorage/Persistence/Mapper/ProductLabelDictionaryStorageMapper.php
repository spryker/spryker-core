<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelStorage\Persistence\Mapper;

use Generated\Shared\Transfer\ProductLabelDictionaryStorageTransfer;
use Orm\Zed\ProductLabelStorage\Persistence\SpyProductLabelDictionaryStorage;
use Propel\Runtime\Collection\ObjectCollection;

class ProductLabelDictionaryStorageMapper
{
    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\ProductLabelStorage\Persistence\SpyProductLabelDictionaryStorage[] $productLabelDictionaryStorageEntities
     * @param \Generated\Shared\Transfer\ProductLabelDictionaryStorageTransfer[] $productLabelDictionaryStorageTransfers
     *
     * @return array
     */
    public function mapProductLabelDictionaryStorageEntitiesToProductLabelDictionaryStorageTransfers(
        ObjectCollection $productLabelDictionaryStorageEntities,
        array $productLabelDictionaryStorageTransfers
    ): array {
        foreach ($productLabelDictionaryStorageEntities as $productLabelDictionaryStorageEntity) {
            $productLabelDictionaryStorageTransfers[] = $this->mapProductLabelDictionaryStorageEntityToProductLabelDictionaryStorageTransfer(
                $productLabelDictionaryStorageEntity,
                new ProductLabelDictionaryStorageTransfer()
            );
        }

        return $productLabelDictionaryStorageTransfers;
    }

    /**
     * @param \Orm\Zed\ProductLabelStorage\Persistence\SpyProductLabelDictionaryStorage $productLabelDictionaryStorageEntity
     * @param \Generated\Shared\Transfer\ProductLabelDictionaryStorageTransfer $productLabelDictionaryStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductLabelDictionaryStorageTransfer
     */
    protected function mapProductLabelDictionaryStorageEntityToProductLabelDictionaryStorageTransfer(
        SpyProductLabelDictionaryStorage $productLabelDictionaryStorageEntity,
        ProductLabelDictionaryStorageTransfer $productLabelDictionaryStorageTransfer
    ): ProductLabelDictionaryStorageTransfer {
        return $productLabelDictionaryStorageTransfer->fromArray($productLabelDictionaryStorageEntity->toArray(), true);
    }
}
