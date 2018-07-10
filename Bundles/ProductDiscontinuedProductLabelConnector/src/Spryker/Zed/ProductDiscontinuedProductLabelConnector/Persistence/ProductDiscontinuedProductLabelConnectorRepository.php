<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedProductLabelConnector\Persistence;

use Orm\Zed\ProductDiscontinued\Persistence\Map\SpyProductDiscontinuedTableMap;
use Orm\Zed\ProductLabel\Persistence\SpyProductLabel;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductDiscontinuedProductLabelConnector\Persistence\ProductDiscontinuedProductLabelConnectorPersistenceFactory getFactory()
 */
class ProductDiscontinuedProductLabelConnectorRepository extends AbstractRepository implements ProductDiscontinuedProductLabelConnectorRepositoryInterface
{
    /**
     * @param string $labelName
     *
     * @return null|\Orm\Zed\ProductLabel\Persistence\SpyProductLabel
     */
    public function findProductLabelByName(string $labelName): ?SpyProductLabel
    {
        $productLabelEntity = $this->getFactory()
            ->createProductLabelPropelQuery()
            ->filterByName($labelName);

        if (!$productLabelEntity) {
            return null;
        }

        return $productLabelEntity->findOne();
    }

    /**
     * @return int[]
     */
    public function getProductConcreteIds(): array
    {
        $productConcreteIds = $this->getFactory()
            ->createProductDiscontinuedPropelQuery()
            ->select([SpyProductDiscontinuedTableMap::COL_FK_PRODUCT])
            ->groupByFkProduct()
            ->find();

        if (!$productConcreteIds) {
            return [];
        }

        return $productConcreteIds->getData();
    }
}
