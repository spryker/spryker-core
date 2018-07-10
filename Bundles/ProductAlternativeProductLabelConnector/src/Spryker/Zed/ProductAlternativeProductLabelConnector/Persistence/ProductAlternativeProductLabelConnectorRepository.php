<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeProductLabelConnector\Persistence;

use Orm\Zed\ProductAlternative\Persistence\Map\SpyProductAlternativeTableMap;
use Orm\Zed\ProductLabel\Persistence\SpyProductLabel;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductAlternativeProductLabelConnector\Persistence\ProductAlternativeProductLabelConnectorPersistenceFactory getFactory()
 */
class ProductAlternativeProductLabelConnectorRepository extends AbstractRepository implements ProductAlternativeProductLabelConnectorRepositoryInterface
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
     * @return array
     */
    public function getProductConcreteIds(): array
    {
        $productConcreteIds = $this->getFactory()
            ->createProductAlternativePropelQuery()
            ->select([SpyProductAlternativeTableMap::COL_FK_PRODUCT])
            ->groupByFkProduct()
            ->find();

        if (!$productConcreteIds) {
            return [];
        }

        return $productConcreteIds->getData();
    }
}
