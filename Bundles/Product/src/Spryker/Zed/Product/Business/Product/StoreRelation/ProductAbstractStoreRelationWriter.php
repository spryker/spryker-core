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
        $storeRelationTransfer->requireIdEntity();

        $currentIdStores = $this->getIdStoresByIdProductAbstract($storeRelationTransfer->getIdEntity());
        $requestedIdStores = $this->findStoreRelationIdStores($storeRelationTransfer);

        $saveIdStores = array_diff($requestedIdStores, $currentIdStores);
        $deleteIdStores = array_diff($currentIdStores, $requestedIdStores);

        $this->addStores($saveIdStores, $storeRelationTransfer->getIdEntity());
        $this->removeStores($deleteIdStores, $storeRelationTransfer->getIdEntity());
    }

    /**
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $storeRelationTransfer
     *
     * @return int[]
     */
    protected function findStoreRelationIdStores(StoreRelationTransfer $storeRelationTransfer)
    {
        if ($storeRelationTransfer->getIdStores() === null) {
            return [];
        }

        return $storeRelationTransfer->getIdStores();
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

        $productAbstractStoreEntities = $this->productQueryContainer
            ->queryProductAbstractStoresByFkProductAbstractAndFkStores($idProductAbstract, $idStores)
            ->find();

        foreach ($productAbstractStoreEntities as $productAbstractStoreEntity) {
            $productAbstractStoreEntity->delete();
        }
    }

    /**
     * @param int $idProductAbstract
     *
     * @return int[]
     */
    protected function getIdStoresByIdProductAbstract($idProductAbstract)
    {
        $storeRelation = $this->productAbstractStoreRelationReader->getStoreRelation(
            (new StoreRelationTransfer())
                ->setIdEntity($idProductAbstract)
        );
        
        return $storeRelation->getIdStores();
    }
}
