<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product\StoreRelation;

use Generated\Shared\Transfer\StoreRelationTransfer;
use Orm\Zed\Product\Persistence\SpyProductAbstractStore;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;

class ProductAbstractStoreRelationSaver implements ProductAbstractStoreRelationSaverInterface
{
    /**
     * @var \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\Product\Business\Product\StoreRelation\ProductAbstractStoreRelationReaderInterface
     */
    protected $productAbstractStoreRelationReader;

    /**
     * @param \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Product\Business\Product\StoreRelation\ProductAbstractStoreRelationReaderInterface $productAbstractStoreRelationReader
     */
    public function __construct(
        ProductQueryContainerInterface $queryContainer,
        ProductAbstractStoreRelationReaderInterface $productAbstractStoreRelationReader
    ) {
        $this->queryContainer = $queryContainer;
        $this->productAbstractStoreRelationReader = $productAbstractStoreRelationReader;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $storeRelationTransfer
     *
     * @return void
     */
    public function save(StoreRelationTransfer $storeRelationTransfer)
    {
        $currentIdStores = $this->getIdStores($storeRelationTransfer->getIdEntity());

        $saveIdStores = array_diff($storeRelationTransfer->getIdStores(), $currentIdStores);
        $deleteIdStores = array_diff($currentIdStores, $storeRelationTransfer->getIdStores());

        $this->addStores($saveIdStores, $storeRelationTransfer->getIdEntity());
        $this->removeStores($deleteIdStores, $storeRelationTransfer->getIdEntity());
    }

    /**
     * @param int[] $idStores
     * @param int $idProductAbstract
     *
     * @return void
     */
    protected function addStores(array $idStores, $idProductAbstract)
    {
        foreach ($idStores as $idStore) {
            $entity = new SpyProductAbstractStore();
            $entity->setFkStore($idStore);
            $entity->setFkProductAbstract($idProductAbstract);
            $entity->save();
        }
    }

    /**
     * @param int[] $idStores
     * @param int $idProductAbstract
     *
     * @return void
     */
    protected function removeStores(array $idStores, $idProductAbstract)
    {
        if (count($idStores) === 0) {
            return;
        }

        $this->queryContainer
            ->queryProductAbstractStoresByFkProductAbstractAndFkStores($idProductAbstract, $idStores)
            ->delete();
    }

    /**
     * @param int $idProductAbstract
     *
     * @return int[]
     */
    protected function getIdStores($idProductAbstract)
    {
        $storeRelation = $this->productAbstractStoreRelationReader->getStoreRelation(
            (new StoreRelationTransfer())
                ->setIdEntity($idProductAbstract)
        );
        
        return $storeRelation->getIdStores();
    }
}
