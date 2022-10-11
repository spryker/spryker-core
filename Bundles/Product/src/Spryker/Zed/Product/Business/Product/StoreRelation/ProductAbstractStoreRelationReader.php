<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product\StoreRelation;

use ArrayObject;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Product\Dependency\Facade\ProductToStoreInterface;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;

class ProductAbstractStoreRelationReader implements ProductAbstractStoreRelationReaderInterface
{
    /**
     * @var \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface
     */
    protected $productQueryContainer;

    /**
     * @var \Spryker\Zed\Product\Dependency\Facade\ProductToStoreInterface
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface $productQueryContainer
     * @param \Spryker\Zed\Product\Dependency\Facade\ProductToStoreInterface $storeFacade
     */
    public function __construct(
        ProductQueryContainerInterface $productQueryContainer,
        ProductToStoreInterface $storeFacade
    ) {
        $this->productQueryContainer = $productQueryContainer;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $storeRelationTransfer
     *
     * @return \Generated\Shared\Transfer\StoreRelationTransfer
     */
    public function getStoreRelation(StoreRelationTransfer $storeRelationTransfer)
    {
        $storeRelationTransfer->requireIdEntity();

        $storeTransferCollection = $this->getRelatedStores($storeRelationTransfer->getIdEntity());
        $idStores = $this->getIdStores($storeTransferCollection);

        $storeRelationTransfer
            ->setStores($storeTransferCollection)
            ->setIdStores($idStores);

        return $storeRelationTransfer;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\StoreTransfer>
     */
    protected function getRelatedStores($idProductAbstract)
    {
        // Load full Store objects.
        $storesByName = $this->getAllStoresIndexedByStoreName();
        $productAbstractStoreCollection = $this->productQueryContainer
            ->queryProductAbstractStoreWithStoresByFkProductAbstract($idProductAbstract)
            ->find();

        $relatedStores = new ArrayObject();
        foreach ($productAbstractStoreCollection as $productAbstractStoreEntity) {
            $storeEntity = $productAbstractStoreEntity->getSpyStore();

            if (isset($storesByName[$storeEntity->getName()])) {
                $relatedStores->append($storesByName[$storeEntity->getName()]);
            } else {
                $relatedStores->append(
                    (new StoreTransfer())
                        ->fromArray(
                            $storeEntity->toArray(),
                            true,
                        ),
                );
            }
        }

        return $relatedStores;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\StoreTransfer> $storeTransferCollection
     *
     * @return array<int>
     */
    protected function getIdStores(ArrayObject $storeTransferCollection)
    {
        return array_map(function (StoreTransfer $storeTransfer) {
            return $storeTransfer->getIdStore();
        }, $storeTransferCollection->getArrayCopy());
    }

    /**
     * @return array<\Generated\Shared\Transfer\StoreTransfer>
     */
    protected function getAllStoresIndexedByStoreName(): array
    {
        $storesByName = [];

        foreach ($this->storeFacade->getAllStores() as $storeTransfer) {
            $storesByName[$storeTransfer->getName()] = $storeTransfer;
        }

        return $storesByName;
    }
}
