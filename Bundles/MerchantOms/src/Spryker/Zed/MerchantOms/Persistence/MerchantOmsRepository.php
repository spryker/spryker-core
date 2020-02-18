<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOms\Persistence;

use Generated\Shared\Transfer\MerchantOmsProcessCriteriaFilterTransfer;
use Generated\Shared\Transfer\MerchantOmsProcessTransfer;
use Orm\Zed\MerchantOms\Persistence\SpyMerchantOmsProcessQuery;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\MerchantOms\Persistence\MerchantOmsPersistenceFactory getFactory()
 */
class MerchantOmsRepository extends AbstractRepository implements MerchantOmsRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantOmsProcessCriteriaFilterTransfer $merchantOmsProcessCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOmsProcessTransfer|null
     */
    public function findMerchantOmsProcess(MerchantOmsProcessCriteriaFilterTransfer $merchantOmsProcessCriteriaFilterTransfer): ?MerchantOmsProcessTransfer
    {
        $merchantOmsProcessQuery = $this->getFactory()->createMerchantOmsProcessQuery();
        $merchantOmsProcessQuery = $this->applyFilters($merchantOmsProcessCriteriaFilterTransfer, $merchantOmsProcessQuery);

        $merchantOmsProcessEntity = $merchantOmsProcessQuery->findOne();

        if (!$merchantOmsProcessEntity) {
            return null;
        }

        return $this->getFactory()
            ->createMerchantOmsMapper()
            ->mapMerchantOmsProcessEntityToMerchantOmsProcessTransfer(
                $merchantOmsProcessEntity,
                new MerchantOmsProcessTransfer()
            );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOmsProcessCriteriaFilterTransfer $merchantOmsProcessCriteriaFilterTransfer
     * @param \Orm\Zed\MerchantOms\Persistence\SpyMerchantOmsProcessQuery $merchantOmsProcessQuery
     *
     * @return \Orm\Zed\MerchantOms\Persistence\SpyMerchantOmsProcessQuery
     */
    protected function applyFilters(
        MerchantOmsProcessCriteriaFilterTransfer $merchantOmsProcessCriteriaFilterTransfer,
        SpyMerchantOmsProcessQuery $merchantOmsProcessQuery
    ): SpyMerchantOmsProcessQuery {
        if ($merchantOmsProcessCriteriaFilterTransfer->getMerchantReference()) {
            $merchantOmsProcessQuery
                ->useSpyMerchantQuery()
                    ->filterByMerchantReference($merchantOmsProcessCriteriaFilterTransfer->getMerchantReference())
                ->endUse();
        }

        return $merchantOmsProcessQuery;
    }
}
