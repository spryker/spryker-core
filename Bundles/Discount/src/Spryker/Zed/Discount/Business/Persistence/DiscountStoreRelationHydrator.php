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
use Propel\Runtime\Collection\ObjectCollection;

class DiscountStoreRelationHydrator implements DiscountStoreRelationHydratorInterface
{
    /**
     * @param \Orm\Zed\Discount\Persistence\SpyDiscount $discount
     *
     * @return \Generated\Shared\Transfer\StoreRelationTransfer
     */
    public function hydrate(SpyDiscount $discount)
    {
        $relatedStoreTransferCollection = $this->getRelatedStores($discount->getSpyDiscountStores());
        $idStores = $this->getIdStores($relatedStoreTransferCollection);

        $storeRelation = (new StoreRelationTransfer())
            ->setIdEntity($discount->getIdDiscount())
            ->setStores($relatedStoreTransferCollection)
            ->setIdStores($idStores);

        return $storeRelation;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\Discount\Persistence\SpyDiscountStore[] $discountStores
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\StoreTransfer[]
     */
    protected function getRelatedStores(ObjectCollection $discountStores)
    {
        $relatedStoreTransferCollection = new ArrayObject();
        foreach ($discountStores as $discountStoreEntity) {
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
     * @param \ArrayObject|\Generated\Shared\Transfer\StoreTransfer[] $relatedStores
     *
     * @return int[]
     */
    protected function getIdStores(ArrayObject $relatedStores)
    {
        return array_map(function (StoreTransfer $store) {
            return $store->getIdStore();
        }, $relatedStores->getArrayCopy());
    }
}
