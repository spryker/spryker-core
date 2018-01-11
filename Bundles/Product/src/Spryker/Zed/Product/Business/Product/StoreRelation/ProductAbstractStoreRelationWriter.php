<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product\StoreRelation;

use Generated\Shared\Transfer\StoreRelationTransfer;
use Orm\Zed\Product\Persistence\SpyProductAbstractStore;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;

class ProductAbstractStoreRelationWriter implements ProductAbstractStoreRelationWriterInterface
{
    /**
     * @var \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface
     */
    protected $productQueryContainer;

    /**
     * @var \Spryker\Zed\Product\Business\Product\StoreRelation\ProductAbstractStoreRelationReaderInterface
     */
    protected $productAbstractStoreRelationReader;

    /**
     * @param \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface $productQueryContainer
     * @param \Spryker\Zed\Product\Business\Product\StoreRelation\ProductAbstractStoreRelationReaderInterface $productAbstractStoreRelationReader
     */
    public function __construct(
        ProductQueryContainerInterface $productQueryContainer,
        ProductAbstractStoreRelationReaderInterface $productAbstractStoreRelationReader
    ) {
        $this->productQueryContainer = $productQueryContainer;
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
            (new SpyProductAbstractStore())
                ->setFkStore($idStore)
                ->setFkProductAbstract($idProductAbstract)
                ->save();
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

        $this->productQueryContainer
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
