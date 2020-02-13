<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Persistence\Mapper;

use Generated\Shared\Transfer\ProductLabelTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\ProductLabel\Persistence\SpyProductLabel;
use Orm\Zed\Store\Persistence\SpyStore;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Shared\ProductLabel\ProductLabelConstants;

class ProductLabelMapper
{
    /**
     * @param \Orm\Zed\ProductLabel\Persistence\SpyProductLabel $productLabelEntity
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @return \Generated\Shared\Transfer\ProductLabelTransfer
     */
    public function mapProductLabelEntityToProductLabelTransfer(
        SpyProductLabel $productLabelEntity,
        ProductLabelTransfer $productLabelTransfer
    ): ProductLabelTransfer {
        $productLabelTransfer->fromArray($productLabelEntity->toArray(), true);

        $productLabelTransfer->setValidFrom(
            $productLabelEntity->getValidFrom(ProductLabelConstants::VALIDITY_DATE_FORMAT)
        );
        $productLabelTransfer->setValidTo(
            $productLabelEntity->getValidTo(ProductLabelConstants::VALIDITY_DATE_FORMAT)
        );

        return $productLabelTransfer;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\ProductLabel\Persistence\SpyProductLabelStore[] $productLabelStoreEntities
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $storeRelationTransfer
     *
     * @return \Generated\Shared\Transfer\StoreRelationTransfer
     */
    public function mapProductLabelStoreEntitiesToStoreRelationTransfer(
        ObjectCollection $productLabelStoreEntities,
        StoreRelationTransfer $storeRelationTransfer
    ): StoreRelationTransfer {
        foreach ($productLabelStoreEntities as $productLabelStoreEntity) {
            $storeRelationTransfer->addStores(
                $this->mapStoreEntityToStoreTransfer($productLabelStoreEntity->getStore(), new StoreTransfer())
            );
            $storeRelationTransfer->addIdStores($productLabelStoreEntity->getFkStore());
        }

        return $storeRelationTransfer;
    }

    /**
     * @param \Orm\Zed\Store\Persistence\SpyStore $storeEntity
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function mapStoreEntityToStoreTransfer(SpyStore $storeEntity, StoreTransfer $storeTransfer): StoreTransfer
    {
        return $storeTransfer->fromArray($storeEntity->toArray(), true);
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\ProductLabel\Persistence\SpyProductLabel[] $productLabelEntities
     * @param array $transferCollection
     *
     * @return \Generated\Shared\Transfer\ProductLabelTransfer[]
     */
    public function mapProductLabelEntitiesToProductLabelTransfers(
        ObjectCollection $productLabelEntities,
        array $transferCollection = []
    ): array {
        foreach ($productLabelEntities as $productLabelEntity) {
            $transferCollection[] = $this->mapProductLabelEntityToProductLabelTransfer($productLabelEntity, new ProductLabelTransfer());
        }

        return $transferCollection;
    }
}
