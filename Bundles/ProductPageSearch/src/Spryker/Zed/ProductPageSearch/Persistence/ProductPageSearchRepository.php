<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchPersistenceFactory getFactory()
 */
class ProductPageSearchRepository extends AbstractRepository implements ProductPageSearchRepositoryInterface
{
    /**
     * @param array $productAbstractIds
     *
     * @return array
     */
    public function getProductAbstractLocalizedEntitiesByIds(array $productAbstractIds): array
    {
        return $this->getFactory()->createProductAbstractByIdsQuery($productAbstractIds)
            ->joinWith('SpyProduct.SpyProductSearch')
            ->find()
            ->getData();
    }
}
