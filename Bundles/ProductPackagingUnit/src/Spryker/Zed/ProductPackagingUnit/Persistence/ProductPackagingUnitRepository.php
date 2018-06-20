<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Persistence;

use Generated\Shared\Transfer\ProductPackagingLeadProductTransfer;
use Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
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
    public function getProductPackagingUnitTypeByName(
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
     * @param int $productPackagingUnitTypeId
     *
     * @return \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer|null
     */
    public function getProductPackagingUnitTypeById(
        int $productPackagingUnitTypeId
    ): ?ProductPackagingUnitTypeTransfer {
        $productPackagingUnitTypeEntity = $this->getFactory()
            ->createProductPackagingUnitTypeQuery()
            ->filterByIdProductPackagingUnitType($productPackagingUnitTypeId)
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
     * @param int $productPackagingUnitTypeId
     *
     * @return int
     */
    public function getCountProductPackagingUnitsForTypeById(
        int $productPackagingUnitTypeId
    ): int {
        return $this->getFactory()
            ->createProductPackagingUnitQuery()
            ->filterByFkProductPackagingUnitType($productPackagingUnitTypeId)
            ->count();
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductPackagingLeadProductTransfer|null
     */
    public function getProductPackagingLeadProductByIdProductAbstract(
        int $idProductAbstract
    ): ?ProductPackagingLeadProductTransfer {
        $productPackagingLeadProductEntity = $this->getFactory()
            ->createProductPackagingLeadProductQuery()
            ->filterByFkProductAbstract($idProductAbstract)
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
}
