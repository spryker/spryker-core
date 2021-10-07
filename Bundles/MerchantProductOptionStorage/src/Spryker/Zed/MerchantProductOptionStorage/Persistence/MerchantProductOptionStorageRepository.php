<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOptionStorage\Persistence;

use Orm\Zed\ProductOption\Persistence\Map\SpyProductAbstractProductOptionGroupTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\MerchantProductOptionStorage\Persistence\MerchantProductOptionStoragePersistenceFactory getFactory()
 */
class MerchantProductOptionStorageRepository extends AbstractRepository implements MerchantProductOptionStorageRepositoryInterface
{
    /**
     * @param array<int> $merchantProductOptionGroupIds
     *
     * @return array<int>
     */
    public function getAbstractProductIdsByMerchantProductOptionGroupIds(array $merchantProductOptionGroupIds): array
    {
        /**
         * @phpstan-var \Orm\Zed\MerchantProductOption\Persistence\SpyMerchantProductOptionGroupQuery<mixed> $merchantProductOptionGroupQuery
         *
         * @var \Orm\Zed\MerchantProductOption\Persistence\SpyMerchantProductOptionGroupQuery $merchantProductOptionGroupQuery
         */
        $merchantProductOptionGroupQuery = $this->getFactory()
            ->getMerchantProductOptionGroupPropelQuery()
            ->select([SpyProductAbstractProductOptionGroupTableMap::COL_FK_PRODUCT_ABSTRACT])
            ->useSpyProductOptionGroupQuery()
                ->innerJoinSpyProductAbstractProductOptionGroup()
            ->endUse();
        $merchantProductOptionGroupQuery->filterByIdMerchantProductOptionGroup_In($merchantProductOptionGroupIds);

        return $merchantProductOptionGroupQuery->find()->getData();
    }
}
