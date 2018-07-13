<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeProductLabelConnector\Persistence;

use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\ProductAlternative\Persistence\Map\SpyProductAlternativeTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductAlternativeProductLabelConnector\Persistence\ProductAlternativeProductLabelConnectorPersistenceFactory getFactory()
 */
class ProductAlternativeProductLabelConnectorRepository extends AbstractRepository implements ProductAlternativeProductLabelConnectorRepositoryInterface
{
    /**
     * @param string $labelName
     *
     * @return bool
     */
    public function getIsProductLabelActive(string $labelName): bool
    {
        $productLabelEntity = $this->getFactory()
            ->getProductLabelPropelQuery()
            ->filterByName($labelName)
            ->findOne();

        if (!$productLabelEntity) {
            return false;
        }

        return $productLabelEntity->getIsActive();
    }

    /**
     * @return int[]
     */
    public function getProductConcreteIds(): array
    {
        $productConcreteIds = $this->getFactory()
            ->getProductAlternativePropelQuery()
            ->select([SpyProductAlternativeTableMap::COL_FK_PRODUCT])
            ->groupByFkProduct()
            ->find();

        if (!$productConcreteIds) {
            return [];
        }

        return $productConcreteIds->getData();
    }

    /**
     * @param int $idProductAbstract
     *
     * @return int[]
     */
    public function getProductConcreteIdsByAbstractProductId(int $idProductAbstract): array
    {
        $productConcreteIds = $this->getFactory()
            ->getProductPropelQuery()
            ->select([SpyProductTableMap::COL_ID_PRODUCT])
            ->filterByFkProductAbstract($idProductAbstract)
            ->find();

        if (!$productConcreteIds) {
            return [];
        }

        return $productConcreteIds->getData();
    }

    /**
     * @return int[]
     */
    public function getProductAbstractIdsForAlternative(): array
    {
        $productAbstractIds = $this->getFactory()
            ->getProductPropelQuery()
            ->select([SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT])
            ->filterByIdProduct_In($this->getProductConcreteIds())
            ->groupByFkProductAbstract()
            ->find();

        if (!$productAbstractIds) {
            return [];
        }

        return $productAbstractIds->getData();
    }
}
