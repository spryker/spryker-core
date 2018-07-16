<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeProductLabelConnector\Persistence;

use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductAlternativeProductLabelConnector\Persistence\ProductAlternativeProductLabelConnectorPersistenceFactory getFactory()
 */
class ProductAlternativeProductLabelConnectorRepository extends AbstractRepository implements ProductAlternativeProductLabelConnectorRepositoryInterface
{
    /**
     * @module Product
     *
     * @return int[]
     */
    public function getProductAbstractIdsForAlternative(): array
    {
        return $this->getFactory()
            ->getProductAlternativePropelQuery()
            ->leftJoinProductConcrete()
            ->useProductConcreteQuery()
                ->groupByFkProductAbstract()
            ->endUse()
            ->select([SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT])
            ->find()
            ->toArray();
    }
}
