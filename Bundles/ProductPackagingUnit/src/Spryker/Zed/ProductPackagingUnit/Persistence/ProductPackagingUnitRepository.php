<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Persistence;

use Generated\Shared\Transfer\ProductPackagingLeadProductTransfer;
use Generated\Shared\Transfer\ProductPackagingUnitTransfer;
use Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnitQuery;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductPackagingUnit\Persistence\ProductPackagingUnitPersistenceFactory getFactory()
 */
class ProductPackagingUnitRepository extends AbstractRepository implements ProductPackagingUnitRepositoryInterface
{
    /**
     * @param string $productPackagingUnitTypeName
     *
     * @return \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer|null
     */
    public function findProductPackagingUnitTypeByName(
        string $productPackagingUnitTypeName
    ): ?ProductPackagingUnitTypeTransfer {
        $productPackagingUnitTypeEntity = $this->getFactory()
            ->createProductPackagingUnitTypeQuery()
            ->filterByName($productPackagingUnitTypeName)
            ->findOne();

        if ($productPackagingUnitTypeEntity) {
            return $this->getFactory()
                ->createProductPackagingUnitMapper()
                ->mapProductPackagingUnitTypeTransfer(
                    $productPackagingUnitTypeEntity,
                    new ProductPackagingUnitTypeTransfer()
                );
        }

        return null;
    }

    /**
     * @param int $idProductPackagingUnitType
     *
     * @return \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer|null
     */
    public function findProductPackagingUnitTypeById(
        int $idProductPackagingUnitType
    ): ?ProductPackagingUnitTypeTransfer {
        $productPackagingUnitTypeEntity = $this->getFactory()
            ->createProductPackagingUnitTypeQuery()
            ->filterByIdProductPackagingUnitType($idProductPackagingUnitType)
            ->findOne();

        if ($productPackagingUnitTypeEntity) {
            return $this->getFactory()
                ->createProductPackagingUnitMapper()
                ->mapProductPackagingUnitTypeTransfer(
                    $productPackagingUnitTypeEntity,
                    new ProductPackagingUnitTypeTransfer()
                );
        }

        return null;
    }

    /**
     * @param int $idProductPackagingUnitType
     *
     * @return int
     */
    public function countProductPackagingUnitsByTypeId(
        int $idProductPackagingUnitType
    ): int {
        return $this->getFactory()
            ->createProductPackagingUnitQuery()
            ->filterByFkProductPackagingUnitType($idProductPackagingUnitType)
            ->count();
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductPackagingLeadProductTransfer|null
     */
    public function findProductPackagingLeadProductByIdProductAbstract(
        int $idProductAbstract
    ): ?ProductPackagingLeadProductTransfer {
        $productPackagingLeadProductEntity = $this->getFactory()
            ->createProductPackagingLeadProductQuery()
            ->filterByFkProductAbstract($idProductAbstract)
            ->innerJoinSpyProduct()
            ->findOne();

        if (!$productPackagingLeadProductEntity) {
            return null;
        }

        $productPackagingLeadProductTransfer = $this->getFactory()
            ->createProductPackagingUnitMapper()
            ->mapProductPackagingLeadProductTransfer(
                $productPackagingLeadProductEntity,
                new ProductPackagingLeadProductTransfer()
            );

        return $productPackagingLeadProductTransfer;
    }

    /**
     * @param string $productPackagingUnitSku
     *
     * @return \Generated\Shared\Transfer\ProductPackagingLeadProductTransfer|null
     */
    public function findProductPackagingLeadProductByProductPackagingSku(
        string $productPackagingUnitSku
    ): ?ProductPackagingLeadProductTransfer {
        $productPackagingLeadProductEntity = $this->getFactory()
            ->createProductPackagingLeadProductQuery()
            ->useSpyProductAbstractQuery()
                ->useSpyProductQuery()
                    ->filterBySku($productPackagingUnitSku)
                ->endUse()
            ->endUse()
            ->findOne();

        if (!$productPackagingLeadProductEntity) {
            return null;
        }

        $productPackagingLeadProductTransfer = $this->getFactory()
            ->createProductPackagingUnitMapper()
            ->mapProductPackagingLeadProductTransfer(
                $productPackagingLeadProductEntity,
                new ProductPackagingLeadProductTransfer()
            );

        return $productPackagingLeadProductTransfer;
    }

    /**
     * @uses Product
     *
     * @param array $productPackagingUnitTypeIds
     *
     * @return array
     */
    public function getIdProductAbstractsByIdProductPackagingUnitTypes(array $productPackagingUnitTypeIds): array
    {
        $query = $this->getFactory()
            ->createProductPackagingUnitQuery()
            ->innerJoinWithProductPackagingUnitType()
            ->useProductPackagingUnitTypeQuery()
                ->filterByIdProductPackagingUnitType_In($productPackagingUnitTypeIds)
            ->endUse()
            ->innerJoinWithProduct()
            ->useProductQuery()
                ->innerJoinWithSpyProductAbstract()
                ->useSpyProductAbstractQuery()
                    ->select([SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT])
                ->endUse()
            ->endUse();

        return $this->buildQueryFromCriteria($query)->find();
    }

    /**
     * @param int $idProductPackagingUnit
     *
     * @return \Generated\Shared\Transfer\ProductPackagingUnitTransfer|null
     */
    public function findProductPackagingUnitById(
        int $idProductPackagingUnit
    ): ?ProductPackagingUnitTransfer {
        $productPackagingUnitEntity = $this->getProductPackagingUnitCriteria()
            ->findOneByIdProductPackagingUnit($idProductPackagingUnit);

        if (!$productPackagingUnitEntity) {
            return null;
        }

        $productPackagingUnitTransfer = $this->getFactory()
            ->createProductPackagingUnitMapper()
            ->mapProductPackagingUnitTransfer(
                $productPackagingUnitEntity,
                new ProductPackagingUnitTransfer()
            );

        return $productPackagingUnitTransfer;
    }

    /**
     * @return \Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnitQuery
     */
    protected function getProductPackagingUnitCriteria(): SpyProductPackagingUnitQuery
    {
        return $this->getFactory()
            ->createProductPackagingUnitQuery()
            ->innerJoinProductPackagingUnitType()
            ->leftJoinSpyProductPackagingUnitAmount();
    }
}
