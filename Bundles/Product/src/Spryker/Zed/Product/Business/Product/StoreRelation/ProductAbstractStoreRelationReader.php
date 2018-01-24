<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product\StoreRelation;

use ArrayObject;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;

class ProductAbstractStoreRelationReader implements ProductAbstractStoreRelationReaderInterface
{
    /**
     * @var \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface
     */
    protected $productQueryContainer;

    /**
     * @param \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface $productQueryContainer
     */
    public function __construct(ProductQueryContainerInterface $productQueryContainer)
    {
        $this->productQueryContainer = $productQueryContainer;
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
     * @param int $idProductAbstract
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\StoreTransfer[]
     */
    protected function getRelatedStores($idProductAbstract)
    {
        $productAbstractStoreCollection = $this->productQueryContainer
            ->queryProductAbstractStoreWithStoresByFkProductAbstract($idProductAbstract)
            ->find();

        $relatedStores = new ArrayObject();
        foreach ($productAbstractStoreCollection as $productAbstractStoreEntity) {
            $relatedStores->append(
                (new StoreTransfer())
                    ->fromArray(
                        $productAbstractStoreEntity->getSpyStore()->toArray(),
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
