<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedProductLabelConnector\Persistence;

use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductDiscontinuedProductLabelConnector\Persistence\ProductDiscontinuedProductLabelConnectorPersistenceFactory getFactory()
 */
class ProductDiscontinuedProductLabelConnectorRepository extends AbstractRepository implements ProductDiscontinuedProductLabelConnectorRepositoryInterface
{
    /**
     * @module Product
     * @module ProductDiscontinued
     *
     * @return int[]
     */
    public function getProductAbstractIdsToBeLabeled(): array
    {
        $productAbstractWithNotDiscontinuedProductConcreteQuery = $this->getFactory()
            ->getProductPropelQuery()
            ->useSpyProductDiscontinuedQuery(null, Criteria::LEFT_JOIN)
                ->filterByIdProductDiscontinued(null, Criteria::ISNULL)
            ->endUse()
            ->select([SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT]);

        return $this->getFactory()
            ->getProductPropelQuery()
            ->filterByFkProductAbstract($productAbstractWithNotDiscontinuedProductConcreteQuery, Criteria::NOT_IN)
            ->distinct()
            ->select([SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT])
            ->find()
            ->toArray();
    }
}
