<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitStorage\Persistence;

use ArrayObject;
use Generated\Shared\Transfer\ProductAbstractPackagingStorageTransfer;
use Generated\Shared\Transfer\ProductConcretePackagingStorageTransfer;
use Generated\Shared\Transfer\ProductPackagingLeadProductTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Orm\Zed\ProductPackagingUnitStorage\Persistence\SpyProductAbstractPackagingStorage;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\ProductPackagingUnitStorage\Persistence\ProductPackagingUnitStoragePersistenceFactory getFactory()
 */
class ProductPackagingUnitStorageQueryContainer extends AbstractQueryContainer implements ProductPackagingUnitStorageQueryContainerInterface
{
    /**
     * @api
     *
     * @param array $productAbstractIds
     *
     * @return \Orm\Zed\ProductPackagingUnitStorage\Persistence\SpyProductAbstractPackagingStorage[]|\Propel\Runtime\Collection\ObjectCollection
     */
    public function getProductAbstractPackagingStorageByProductAbstractIds(array $productAbstractIds)
    {
        return $this->getFactory()
            ->createSpyProductAbstractPackagingStorageQuery()
            ->filterByFkProductAbstract_In($productAbstractIds)
            ->find();
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractPackagingStorageTransfer $productAbstractPackagingTransfer
     *
     * @return \Orm\Zed\ProductPackagingUnitStorage\Persistence\SpyProductAbstractPackagingStorage
     */
    public function createProductAbstractPackagingStorage(ProductAbstractPackagingStorageTransfer $productAbstractPackagingTransfer): SpyProductAbstractPackagingStorage
    {
        $storageEntity = new SpyProductAbstractPackagingStorage();
        $storageEntity->setFkProductAbstract($productAbstractPackagingTransfer->getIdProductAbstract());
        $storageEntity->setData($productAbstractPackagingTransfer->toArray());
        $storageEntity->save();

        return $storageEntity;
    }

    /**
     * @api
     *
     * @param \Orm\Zed\ProductPackagingUnitStorage\Persistence\SpyProductAbstractPackagingStorage $productAbstractPackagingStorageEntity
     *
     * @return void
     */
    public function deleteProductAbstractPackagingStorage(SpyProductAbstractPackagingStorage $productAbstractPackagingStorageEntity): void
    {
        $productAbstractPackagingStorageEntity->delete();
    }

    /**
     * @api
     *
     * @param int $productAbstractId
     *
     * @return \Generated\Shared\Transfer\ProductAbstractPackagingStorageTransfer
     */
    public function getProductAbstractPackagingTransferByProductAbstractId(int $productAbstractId): ProductAbstractPackagingStorageTransfer
    {
        $productPackagingLeadProductTransfer = $this->getProductPackagingLeadProductByAbstractId($productAbstractId);
        $packageProductConcreteEntities = $this->queryPackageProductsByAbstractId($productAbstractId)
            ->find();

        return $this->mapProductAbstractPackagingTransfer(
            $productAbstractId,
            $productPackagingLeadProductTransfer,
            $packageProductConcreteEntities
        );
    }

    /**
     * @param int $productAbstractId
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    protected function queryPackageProductsByAbstractId(int $productAbstractId): SpyProductQuery
    {
        return $this->getFactory()
            ->getProductQueryContainer()
            ->queryProduct()
            ->filterByFkProductAbstract($productAbstractId)
            ->where(sprintf(
                "%s = true",
                SpyProductTableMap::COL_IS_ACTIVE
            ))
            ->useSpyProductPackagingUnitQuery()
            ->leftJoinProductPackagingUnitType()
            ->leftJoinSpyProductPackagingUnitAmount()
            ->endUse();
    }

    /**
     * @param int $productAbstractId
     *
     * @return \Generated\Shared\Transfer\ProductPackagingLeadProductTransfer|null
     */
    protected function getProductPackagingLeadProductByAbstractId(int $productAbstractId): ?ProductPackagingLeadProductTransfer
    {
        return $this->getFactory()
            ->getProductPackagingUnitFacade()
            ->getProductPackagingLeadProductByAbstractId($productAbstractId);
    }

    /**
     * @param int $productAbstractId
     * @param \Generated\Shared\Transfer\ProductPackagingLeadProductTransfer $productPackagingLeadProductTransfer
     * @param \Orm\Zed\Product\Persistence\SpyProduct[] $packageProductConcreteEntities
     *
     * @return \Generated\Shared\Transfer\ProductAbstractPackagingStorageTransfer
     */
    protected function mapProductAbstractPackagingTransfer(
        int $productAbstractId,
        $productPackagingLeadProductTransfer,
        $packageProductConcreteEntities
    ): ProductAbstractPackagingStorageTransfer {
        $idProduct = ($productPackagingLeadProductTransfer)? $productPackagingLeadProductTransfer->getIdProduct() : null;
        $productAbstractPackagingStorageTransfer = (new ProductAbstractPackagingStorageTransfer())
            ->setIdProductAbstract($productAbstractId)
            ->setLeadProduct($idProduct);
        $defaultPackagingUnitTypeName = $this->getFactory()->getProductPackagingUnitFacade()->getDefaultPackagingUnitTypeName();
        $productAbstractPackagingTypes = [];

        foreach ($packageProductConcreteEntities as $packageProductConcreteEntity) {
            $productAbstractPackagingType = (new ProductConcretePackagingStorageTransfer())
                ->setIdProduct($packageProductConcreteEntity->getIdProduct());

            $productPackagingUnitEntities = $packageProductConcreteEntity->getSpyProductPackagingUnits();
            if (!count($productPackagingUnitEntities)) {
                $productAbstractPackagingTypes[] = $productAbstractPackagingType;
                continue;
            }
            $productPackagingUnitEntity = $productPackagingUnitEntities[0];
            $productPackagingUnitTypeName = $productPackagingUnitEntity->getProductPackagingUnitType()->getName() ?? $defaultPackagingUnitTypeName;
            $productAbstractPackagingType
                ->setName($productPackagingUnitTypeName)
                ->setHasLeadProduct($productPackagingUnitEntity->hasLeadProduct());

            $productPackagingUnitAmounts = $productPackagingUnitEntity->getSpyProductPackagingUnitAmounts();
            if (!count($productPackagingUnitAmounts)) {
                $productAbstractPackagingTypes[] = $productAbstractPackagingType;
                continue;
            }
            $productPackagingUnitAmount = $productPackagingUnitAmounts[0];

            $productAbstractPackagingType
                ->setDefaultAmount($productPackagingUnitAmount->getDefaultAmount())
                ->setIsVariable($productPackagingUnitAmount->getIsVariable())
                ->setAmountMin($productPackagingUnitAmount->getAmountMin())
                ->setAmountMax($productPackagingUnitAmount->getAmountMax())
                ->setAmountInterval($productPackagingUnitAmount->getAmountInterval());

            $productAbstractPackagingTypes[] = $productAbstractPackagingType;
        }
        $productAbstractPackagingStorageTransfer->setTypes(new ArrayObject($productAbstractPackagingTypes));

        return $productAbstractPackagingStorageTransfer;
    }
}
