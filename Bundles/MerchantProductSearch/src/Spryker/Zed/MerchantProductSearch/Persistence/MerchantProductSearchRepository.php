<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductSearch\Persistence;

use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\MerchantProductSearch\Persistence\MerchantProductSearchPersistenceFactory getFactory()
 */
class MerchantProductSearchRepository extends AbstractRepository implements MerchantProductSearchRepositoryInterface
{
    /**
     * @param int[] $merchantIds
     *
     * @return int[]
     */
    public function getProductAbstractIdsByMerchantIds(array $merchantIds): array
    {
        $merchantProductAbstractPropelQuery = $this->getFactory()
            ->getMerchantProductAbstractPropelQuery()
            ->useMerchantQuery()
                ->filterByIsActive(true)
            ->endUse()
            ->filterByFkMerchant_In($merchantIds);

        return $merchantProductAbstractPropelQuery
            ->select([SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT])
            ->find()
            ->getData();
    }
}
