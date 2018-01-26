<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Persistence;

use ArrayObject;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Discount\Persistence\SpyDiscount;
use Traversable;

class DiscountStoreRelationMapper implements DiscountStoreRelationMapperInterface
{
    /**
     * @param \Orm\Zed\Discount\Persistence\SpyDiscount $discountEntity
     *
     * @return \Generated\Shared\Transfer\StoreRelationTransfer
     */
    public function mapDiscountStoreEntityCollectionToStoreRelationTransferCollection(SpyDiscount $discountEntity)
    {
        $relatedStoreTransferCollection = $this->getRelatedStoreTransferCollection($discountEntity->getSpyDiscountStores());
        $idStores = $this->getIdStores($relatedStoreTransferCollection);

        $storeRelationTransfer = (new StoreRelationTransfer())
            ->setIdEntity($discountEntity->getIdDiscount())
            ->setStores($relatedStoreTransferCollection)
            ->setIdStores($idStores);

        return $storeRelationTransfer;
    }

    /**
     * @param \Traversable|\Orm\Zed\Discount\Persistence\SpyDiscountStore[] $discountStoreEntityCollection
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\StoreTransfer[]
     */
    protected function getRelatedStoreTransferCollection(Traversable $discountStoreEntityCollection)
    {
        $relatedStoreTransferCollection = new ArrayObject();
        foreach ($discountStoreEntityCollection as $discountStoreEntity) {
            $relatedStoreTransferCollection->append(
                (new StoreTransfer())
                    ->fromArray(
                        $discountStoreEntity->getSpyStore()->toArray(),
                        true
                    )
            );
        }

        return $relatedStoreTransferCollection;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\StoreTransfer[] $storeTransferCollection
     *
     * @return int[]
     */
    protected function getIdStores(ArrayObject $storeTransferCollection)
    {
        return array_map(function (StoreTransfer $store) {
            return $store->getIdStore();
        }, $storeTransferCollection->getArrayCopy());
    }
}
