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
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $storeRelationTransfer
     *
     * @return \Generated\Shared\Transfer\StoreRelationTransfer
     */
    public function getStoreRelation(StoreRelationTransfer $storeRelationTransfer)
    {
        $storeTransferCollection = $this->getRelatedStores($storeRelationTransfer->getIdEntity());

        $idStores = $this->getIdStores($storeTransferCollection);
        $storeRelationTransfer
            ->setStores($storeTransferCollection)
            ->setIdStores($idStores);

        return $storeRelationTransfer;
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
     * @param \ArrayObject|\Generated\Shared\Transfer\StoreTransfer[] $storeTransferCollection
     *
     * @return int[]
     */
    protected function getIdStores(ArrayObject $storeTransferCollection)
    {
        return array_map(function (StoreTransfer $storeTransfer) {
            return $storeTransfer->getIdStore();
        }, $storeTransferCollection->getArrayCopy());
    }
}
