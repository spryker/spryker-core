<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCategory\Persistence;

use Generated\Shared\Transfer\MerchantCategoryCriteriaTransfer;
use Orm\Zed\MerchantCategory\Persistence\SpyMerchantCategoryQuery;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\MerchantCategory\Persistence\MerchantCategoryPersistenceFactory getFactory()
 */
class MerchantCategoryEntityManager extends AbstractEntityManager implements MerchantCategoryEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantCategoryCriteriaTransfer $merchantCategoryCriteriaTransfer
     *
     * @return void
     */
    public function delete(MerchantCategoryCriteriaTransfer $merchantCategoryCriteriaTransfer): void
    {
        $merchantCategoryQuery = $this->getFactory()->getMerchantCategoryPropelQuery();
        $merchantCategoryQuery = $this->applyCriteria($merchantCategoryQuery, $merchantCategoryCriteriaTransfer);
        $merchantCategoryQuery->find()->delete();
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
        if ($merchantCategoryCriteriaTransfer->getCategoryIds()) {
            $merchantCategoryQuery->filterByFkCategory_In($merchantCategoryCriteriaTransfer->getCategoryIds());
        }

        return $merchantCategoryQuery;
    }
}
