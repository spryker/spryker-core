<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCategory\Persistence;

use Generated\Shared\Transfer\MerchantCategoryCriteriaTransfer;
use Generated\Shared\Transfer\MerchantCategoryTransfer;
use Orm\Zed\MerchantCategory\Persistence\SpyMerchantCategoryQuery;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\MerchantCategory\Persistence\MerchantCategoryPersistenceFactory getFactory()
 */
class MerchantCategoryRepository extends AbstractRepository implements MerchantCategoryRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantCategoryCriteriaTransfer $merchantCategoryCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCategoryTransfer[]
     */
    public function get(MerchantCategoryCriteriaTransfer $merchantCategoryCriteriaTransfer): array
    {
        $merchantCategoryQuery = $this->getFactory()->getMerchantCategoryPropelQuery();
        $merchantCategoryQuery->joinWithSpyCategory()
            ->useSpyCategoryQuery()
                ->leftJoinWithAttribute()
                ->useAttributeQuery()
                    ->leftJoinWithLocale()
                ->endUse()
            ->endUse();

        $merchantCategoryQuery = $this->applyCriteria($merchantCategoryQuery, $merchantCategoryCriteriaTransfer);
        $merchantCategoryEntities = $merchantCategoryQuery->find();

        $merchantCategoryTransfers = [];

        foreach ($merchantCategoryEntities as $merchantCategoryEntity) {
            $merchantCategoryTransfers[] = $this->getFactory()
                ->createMerchantCategoryMapper()
                ->mapMerchantCategoryEntityToMerchantCategoryTransfer($merchantCategoryEntity, new MerchantCategoryTransfer());
        }

        return $merchantCategoryTransfers;
    }

    /**
     * @phpstan-param \Orm\Zed\MerchantCategory\Persistence\SpyMerchantCategoryQuery<\Orm\Zed\MerchantCategory\Persistence\SpyMerchantCategory> $merchantCategoryQuery
     *
     * @phpstan-return \Orm\Zed\MerchantCategory\Persistence\SpyMerchantCategoryQuery<\Orm\Zed\MerchantCategory\Persistence\SpyMerchantCategory>
     *
     * @param \Orm\Zed\MerchantCategory\Persistence\SpyMerchantCategoryQuery $merchantCategoryQuery
     * @param \Generated\Shared\Transfer\MerchantCategoryCriteriaTransfer $merchantCategoryCriteriaTransfer
     *
     * @return \Orm\Zed\MerchantCategory\Persistence\SpyMerchantCategoryQuery
     */
    protected function applyCriteria(
        SpyMerchantCategoryQuery $merchantCategoryQuery,
        MerchantCategoryCriteriaTransfer $merchantCategoryCriteriaTransfer
    ): SpyMerchantCategoryQuery {
        if ($merchantCategoryCriteriaTransfer->getIdMerchant()) {
            $merchantCategoryQuery->filterByFkMerchant($merchantCategoryCriteriaTransfer->getIdMerchant());
        }

        if ($merchantCategoryCriteriaTransfer->getCategoryIds()) {
            $merchantCategoryQuery->filterByFkCategory_In($merchantCategoryCriteriaTransfer->getCategoryIds());
        }

        if ($merchantCategoryCriteriaTransfer->getMerchantIds()) {
            $merchantCategoryQuery->filterByFkMerchant($merchantCategoryCriteriaTransfer->getMerchantIds(), Criteria::IN);
        }

        if ($merchantCategoryCriteriaTransfer->getIsCategoryActive()) {
            $merchantCategoryQuery
                ->useSpyCategoryQuery()
                    ->filterByIsActive(true)
                ->endUse();
        }

        return $merchantCategoryQuery;
    }
}
