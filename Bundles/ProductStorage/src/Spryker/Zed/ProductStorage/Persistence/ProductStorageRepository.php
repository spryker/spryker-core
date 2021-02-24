<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductStorage\Persistence;

use Orm\Zed\Product\Persistence\Map\SpyProductAttributeKeyTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductStorage\Persistence\ProductStoragePersistenceFactory getFactory()
 */
class ProductStorageRepository extends AbstractRepository implements ProductStorageRepositoryInterface
{
    /**
     * @param int[] $productConcreteIds
     *
     * @return array
     */
    public function getProductAttributesGroupedByIdProduct(array $productConcreteIds): array
    {
        return $this->getFactory()
            ->getProductPropelQuery()
            ->select([
                SpyProductTableMap::COL_ID_PRODUCT,
                SpyProductTableMap::COL_ATTRIBUTES,
            ])
            ->filterByIdProduct_In($productConcreteIds)
            ->filterByIsActive(true)
            ->find()
            ->toKeyValue(SpyProductTableMap::COL_ID_PRODUCT, SpyProductTableMap::COL_ATTRIBUTES);
    }

    /**
     * @return string[]
     */
    public function getProductAttributeKeys(): array
    {
        return $this->getFactory()
            ->getProductAttributeKeyPropelQuery()
            ->select(SpyProductAttributeKeyTableMap::COL_KEY)
            ->find()
            ->toArray();
    }
}
