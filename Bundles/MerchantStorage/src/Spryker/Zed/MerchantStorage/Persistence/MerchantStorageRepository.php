<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantStorage\Persistence;

use Generated\Shared\Transfer\MerchantCriteriaFilterTransfer;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Formatter\ObjectFormatter;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\MerchantStorage\Persistence\MerchantStoragePersistenceFactory getFactory()
 */
class MerchantStorageRepository extends AbstractRepository implements MerchantStorageRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantCriteriaFilterTransfer $merchantCriteriaFilterTransfer
     *
     * @return \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\MerchantStorage\Persistence\SpyMerchantStorage[]
     */
    public function getFilteredMerchantStorageEntityTransfers(
        MerchantCriteriaFilterTransfer $merchantCriteriaFilterTransfer
    ): ObjectCollection {
        $merchantStorageQuery = $this->getFactory()
            ->createMerchantStorageQuery();

        if ($merchantCriteriaFilterTransfer->getMerchantIds()) {
            $merchantStorageQuery->filterByIdMerchant_In($merchantCriteriaFilterTransfer->getMerchantIds());
        }

        if ($merchantCriteriaFilterTransfer->getFilter()) {
            $merchantStorageQuery = $this->buildQueryFromCriteria(
                $merchantStorageQuery,
                $merchantCriteriaFilterTransfer->getFilter()
            )->setFormatter(ObjectFormatter::class);
        }

        return $merchantStorageQuery->find();
    }
}
