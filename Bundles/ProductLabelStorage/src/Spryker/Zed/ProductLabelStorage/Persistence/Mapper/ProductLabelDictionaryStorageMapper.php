<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelStorage\Persistence\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer;
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
    public function mapProductLabelDictionaryStorageEntityToProductLabelDictionaryStorageTransfer(
        SpyProductLabelDictionaryStorage $productLabelDictionaryStorageEntity,
        ProductLabelDictionaryStorageTransfer $productLabelDictionaryStorageTransfer
    ): ProductLabelDictionaryStorageTransfer {
        $productLabelDictionaryStorageTransfer->fromArray($productLabelDictionaryStorageEntity->toArray(), true);

        $productLabelDictionaryStorageTransfer->setItems(
            $this->mapProductLabelDictionaryItemsToProductLabelDictionaryCollection(
                $productLabelDictionaryStorageEntity->getData()['items'],
                new ArrayObject()
            )
        );

        return $productLabelDictionaryStorageTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelDictionaryStorageTransfer $productLabelDictionaryStorageTransfer
     * @param \Orm\Zed\ProductLabelStorage\Persistence\SpyProductLabelDictionaryStorage $productLabelDictionaryStorageEntity
     *
     * @return \Orm\Zed\ProductLabelStorage\Persistence\SpyProductLabelDictionaryStorage
     */
    public function mapProductLabelDictionaryStorageTransferToProductLabelDictionaryStorageEntity(
        ProductLabelDictionaryStorageTransfer $productLabelDictionaryStorageTransfer,
        SpyProductLabelDictionaryStorage $productLabelDictionaryStorageEntity
    ): SpyProductLabelDictionaryStorage {
        $productLabelDictionaryStorageEntity->fromArray($productLabelDictionaryStorageTransfer->toArray());
        $productLabelDictionaryStorageEntity->setData(array_intersect_key($productLabelDictionaryStorageTransfer->modifiedToArray(), ['items' => []]));
        $productLabelDictionaryStorageEntity->setIsSendingToQueue(true);

        return $productLabelDictionaryStorageEntity;
    }

    /**
     * @param array $productLabelDictionaryItems
     * @param \ArrayObject $productLabelDictionaryCollection
     *
     * @return \ArrayObject
     */
    protected function mapProductLabelDictionaryItemsToProductLabelDictionaryCollection(
        array $productLabelDictionaryItems,
        ArrayObject $productLabelDictionaryCollection
    ): ArrayObject {
        foreach ($productLabelDictionaryItems as $productLabelDictionaryItem) {
            $productLabelDictionaryCollection->append(
                $this->mapProductLabelDictionaryItemToProductLabelDictionaryItemTransfer(
                    $productLabelDictionaryItem,
                    new ProductLabelDictionaryItemTransfer()
                )
            );
        }

        return $productLabelDictionaryCollection;
    }

    /**
     * @param array $productLabelDictionaryItem
     * @param \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer $productLabelDictionaryItemTransfer
     *
     * @return \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer
     */
    protected function mapProductLabelDictionaryItemToProductLabelDictionaryItemTransfer(
        array $productLabelDictionaryItem,
        ProductLabelDictionaryItemTransfer $productLabelDictionaryItemTransfer
    ): ProductLabelDictionaryItemTransfer {
        return $productLabelDictionaryItemTransfer->fromArray($productLabelDictionaryItem, true);
    }
}
