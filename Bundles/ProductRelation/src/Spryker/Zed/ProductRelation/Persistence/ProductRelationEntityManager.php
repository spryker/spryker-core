<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelation\Persistence;

use Generated\Shared\Transfer\ProductRelationTransfer;
use Generated\Shared\Transfer\ProductRelationTypeTransfer;
use Orm\Zed\ProductRelation\Persistence\SpyProductRelation;
use Orm\Zed\ProductRelation\Persistence\SpyProductRelationProductAbstract;
use Orm\Zed\ProductRelation\Persistence\SpyProductRelationStore;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;
use Spryker\Zed\Propel\Persistence\BatchProcessor\ActiveRecordBatchProcessorTrait;

/**
 * @method \Spryker\Zed\ProductRelation\Persistence\ProductRelationPersistenceFactory getFactory()
 */
class ProductRelationEntityManager extends AbstractEntityManager implements ProductRelationEntityManagerInterface
{
    use ActiveRecordBatchProcessorTrait;

    /**
     * @param \Generated\Shared\Transfer\ProductRelationTransfer $productRelationTransfer
     *
     * @return \Generated\Shared\Transfer\ProductRelationTransfer
     */
    public function createProductRelation(ProductRelationTransfer $productRelationTransfer): ProductRelationTransfer
    {
        $productRelationMapper = $this->getFactory()
            ->createProductRelationMapper();

        $productRelationEntity = $productRelationMapper
            ->mapProductRelationTransferToProductRelationEntity(
                $productRelationTransfer,
                new SpyProductRelation()
            );

        $productRelationEntity->save();

        return $productRelationMapper->mapProductRelationEntityToProductRelationTransfer(
            $productRelationEntity,
            $productRelationTransfer
        );
    }

    /**
     * @param int[] $abstractProductIds
     * @param int $idProductRelation
     *
     * @return void
     */
    public function saveRelatedProducts(array $abstractProductIds, int $idProductRelation): void
    {
        foreach ($abstractProductIds as $index => $id) {
            $productRelationProductAbstractEntity = $this->createProductRelationProductAbstractEntity();
            $productRelationProductAbstractEntity->setFkProductRelation($idProductRelation);
            $productRelationProductAbstractEntity->setFkProductAbstract($id);
            $productRelationProductAbstractEntity->setOrder($index + 1);

            $this->persist($productRelationProductAbstractEntity);
        }

        $this->commit();
    }

    /**
     * @param int[] $idStores
     * @param int $idProductRelation
     *
     * @return void
     */
    public function addProductRelationStoreRelationsForStores(
        array $idStores,
        int $idProductRelation
    ): void {
        foreach ($idStores as $idStore) {
            $productRelationStoreEntity = new SpyProductRelationStore();
            $productRelationStoreEntity->setFkStore($idStore)
                ->setFkProductRelation($idProductRelation)
                ->save();
        }
    }

    /**
     * @param int[] $idStores
     * @param int $idProductRelation
     *
     * @return void
     */
    public function removeProductRelationStoreRelationsForStores(
        array $idStores,
        int $idProductRelation
    ): void {
        if ($idStores === []) {
            return;
        }

        $this->getFactory()
            ->createProductRelationStoreQuery()
            ->filterByFkProductRelation($idProductRelation)
            ->filterByFkStore_In($idStores)
            ->find()
            ->delete();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductRelationTypeTransfer $productRelationTypeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductRelationTypeTransfer
     */
    public function saveProductRelationType(ProductRelationTypeTransfer $productRelationTypeTransfer): ProductRelationTypeTransfer
    {
        $productRelationTypeEntity = $this->getFactory()
            ->createProductRelationTypeQuery()
            ->filterByKey($productRelationTypeTransfer->getKey())
            ->findOneOrCreate();

        $productRelationTypeEntity->save();

        return $this->getFactory()
            ->createProductRelationTypeMapper()
            ->mapProductRelationTypeEntityToProductRelationTypeTransfer(
                $productRelationTypeEntity,
                $productRelationTypeTransfer
            );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductRelationTransfer $productRelationTransfer
     *
     * @return \Generated\Shared\Transfer\ProductRelationTransfer|null
     */
    public function updateProductRelation(ProductRelationTransfer $productRelationTransfer): ?ProductRelationTransfer
    {
        $productRelationEntity = $this->getFactory()
            ->createProductRelationQuery()
            ->filterByIdProductRelation($productRelationTransfer->getIdProductRelation())
            ->findOne();

        if (!$productRelationEntity) {
            return null;
        }

        $productRelationMapper = $this->getFactory()->createProductRelationMapper();

        $productRelationEntity = $productRelationMapper->mapProductRelationTransferToProductRelationEntity(
            $productRelationTransfer,
            $productRelationEntity
        );
        $productRelationEntity->save();

        return $productRelationMapper->mapProductRelationEntityToProductRelationTransfer(
            $productRelationEntity,
            $productRelationTransfer
        );
    }

    /**
     * @param int $idProductRelation
     *
     * @return void
     */
    public function removeRelatedProductsByIdProductRelation(int $idProductRelation): void
    {
        $this->getFactory()
            ->createProductRelationProductAbstractQuery()
            ->filterByFkProductRelation($idProductRelation)
            ->find()
            ->delete();
    }

    /**
     * @param int $idProductRelation
     *
     * @return void
     */
    public function deleteProductRelationById(int $idProductRelation): void
    {
        $this->getFactory()
            ->createProductRelationQuery()
            ->filterByIdProductRelation($idProductRelation)
            ->findOne()
            ->delete();
    }

    /**
     * @param int $idProductRelation
     *
     * @return void
     */
    public function deleteProductRelationStoresByIdProductRelation(int $idProductRelation): void
    {
        $this->getFactory()
            ->createProductRelationStoreQuery()
            ->filterByFkProductRelation($idProductRelation)
            ->find()
            ->delete();
    }

    /**
     * @return \Orm\Zed\ProductRelation\Persistence\SpyProductRelationProductAbstract
     */
    protected function createProductRelationProductAbstractEntity(): SpyProductRelationProductAbstract
    {
        return new SpyProductRelationProductAbstract();
    }
}
