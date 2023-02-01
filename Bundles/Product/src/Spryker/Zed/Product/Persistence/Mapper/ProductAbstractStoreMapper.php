<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Persistence\Mapper;

use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Propel\Runtime\Collection\ObjectCollection;

class ProductAbstractStoreMapper
{
    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\Product\Persistence\SpyProductAbstractStore> $productAbstractStoreEntities
     *
     * @return array<\Generated\Shared\Transfer\StoreRelationTransfer>
     */
    public function mapProductAbstractStoreEntitiesToStoreRelationTransfers(
        ObjectCollection $productAbstractStoreEntities
    ): array {
        $storeRelationTransfers = [];
        foreach ($productAbstractStoreEntities as $productAbstractStoreEntity) {
            if (!isset($storeRelationTransfers[$productAbstractStoreEntity->getFkProductAbstract()])) {
                $storeRelationTransfers[$productAbstractStoreEntity->getFkProductAbstract()] = (new StoreRelationTransfer())
                    ->setIdEntity($productAbstractStoreEntity->getFkProductAbstract())
                    ->addStores(
                        (new StoreTransfer())
                            ->fromArray($productAbstractStoreEntity->getSpyStore()->toArray(), true),
                    );

                continue;
            }
            $storeRelationTransfers[$productAbstractStoreEntity->getFkProductAbstract()]
                ->addStores(
                    (new StoreTransfer())
                        ->fromArray($productAbstractStoreEntity->getSpyStore()->toArray(), true),
                );
        }

        return $storeRelationTransfers;
    }
}
