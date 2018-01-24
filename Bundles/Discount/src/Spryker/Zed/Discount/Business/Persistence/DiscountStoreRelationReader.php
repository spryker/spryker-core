<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Persistence;

use ArrayObject;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface;

class DiscountStoreRelationReader implements DiscountStoreRelationReaderInterface
{
    /**
     * @var \Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface
     */
    protected $discountQueryContainer;

    /**
     * @param \Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface $discountQueryContainer
     */
    public function __construct(DiscountQueryContainerInterface $discountQueryContainer)
    {
        $this->discountQueryContainer = $discountQueryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $storeRelation
     *
     * @return \Generated\Shared\Transfer\StoreRelationTransfer
     */
    public function getStoreRelation(StoreRelationTransfer $storeRelation)
    {
        $storeRelation->requireIdEntity();

        $storeTransferCollection = $this->getRelatedStores($storeRelation->getIdEntity());

        $idStores = $this->getIdStores($storeTransferCollection);
        $storeRelation
            ->setStores($storeTransferCollection)
            ->setIdStores($idStores);

        return $storeRelation;
    }

    /**
     * @param int $idDiscount
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\StoreTransfer[]
     */
    protected function getRelatedStores($idDiscount)
    {
        $discountStoreCollection = $this->discountQueryContainer
            ->queryDiscountStoreWithStoresByFkDiscount($idDiscount)
            ->find();

        $relatedStores = new ArrayObject();
        foreach ($discountStoreCollection as $discountStoreEntity) {
            $relatedStores->append(
                (new StoreTransfer())
                    ->fromArray(
                        $discountStoreEntity->getSpyStore()->toArray(),
                        true
                    )
            );
        }
        return $relatedStores;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\StoreTransfer[] $stores
     *
     * @return int[]
     */
    protected function getIdStores(ArrayObject $stores)
    {
        return array_map(function (StoreTransfer $storeTransfer) {
            return $storeTransfer->getIdStore();
        }, $stores->getArrayCopy());
    }
}
