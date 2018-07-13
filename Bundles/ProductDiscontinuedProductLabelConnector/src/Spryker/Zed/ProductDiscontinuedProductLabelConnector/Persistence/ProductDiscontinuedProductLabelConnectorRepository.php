<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedProductLabelConnector\Persistence;

use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\ProductDiscontinued\Persistence\Map\SpyProductDiscontinuedTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductDiscontinuedProductLabelConnector\Persistence\ProductDiscontinuedProductLabelConnectorPersistenceFactory getFactory()
 */
class ProductDiscontinuedProductLabelConnectorRepository extends AbstractRepository implements ProductDiscontinuedProductLabelConnectorRepositoryInterface
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
            ->getProductDiscontinuedPropelQuery()
            ->select([SpyProductDiscontinuedTableMap::COL_FK_PRODUCT])
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
    public function getProductAbstractIdsForDiscontinued(): array
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
