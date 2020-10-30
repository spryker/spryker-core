<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCategory\Persistence;

use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\MerchantCategoryCriteriaTransfer;
use Orm\Zed\MerchantCategory\Persistence\SpyMerchantCategoryQuery;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\MerchantCategory\MerchantCategoryConfig;
use Spryker\Zed\MerchantCategory\Persistence\Exception\MerchantCategoryLimitException;

/**
 * @method \Spryker\Zed\MerchantCategory\Persistence\MerchantCategoryPersistenceFactory getFactory()
 */
class MerchantCategoryRepository extends AbstractRepository implements MerchantCategoryRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantCategoryCriteriaTransfer $merchantCategoryCriteriaTransfer
     *
     * @throws \Spryker\Zed\MerchantCategory\Persistence\Exception\MerchantCategoryLimitException
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer[]
     */
    public function getCategories(MerchantCategoryCriteriaTransfer $merchantCategoryCriteriaTransfer): array
    {
        /**
         * @var \Orm\Zed\MerchantCategory\Persistence\SpyMerchantCategoryQuery $merchantCategoryQuery
         */
        $merchantCategoryQuery = $this->getFactory()
            ->getMerchantCategoryPropelQuery()
            ->joinWithSpyCategory()
            ->useSpyCategoryQuery()
                ->leftJoinWithAttribute()
                ->useAttributeQuery()
                    ->leftJoinWithLocale()
                ->endUse()
            ->endUse();

        $merchantCategoryQuery = $this->applyCriteria($merchantCategoryQuery, $merchantCategoryCriteriaTransfer);

        if ($merchantCategoryQuery->count() > MerchantCategoryConfig::MAX_CATEGORY_SELECT_COUNT) {
            throw new MerchantCategoryLimitException(
                'Maximal merchant category select limit reached. Please adjust configuration.'
            );
        }

        $merchantCategoryEntities = $merchantCategoryQuery->find();

        $categoryTransfers = [];

        foreach ($merchantCategoryEntities as $merchantCategoryEntity) {
            $categoryTransfers[] = $this->getFactory()
                ->createMerchantCategoryMapper()
                ->mapMerchantCategoryEntityToCategoryTransfer($merchantCategoryEntity, new CategoryTransfer());
        }

        return $categoryTransfers;
    }

    /**
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

        return $merchantCategoryQuery;
    }
}
