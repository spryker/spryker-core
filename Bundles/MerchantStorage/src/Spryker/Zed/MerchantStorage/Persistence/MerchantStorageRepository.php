<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantStorage\Persistence;

use Generated\Shared\Transfer\MerchantCriteriaTransfer;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Formatter\ObjectFormatter;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\MerchantStorage\Persistence\MerchantStoragePersistenceFactory getFactory()
 */
class MerchantStorageRepository extends AbstractRepository implements MerchantStorageRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantCriteriaTransfer $merchantCriteriaTransfer
     *
     * @return \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\MerchantStorage\Persistence\SpyMerchantStorage[]
     */
    public function getFilteredMerchantStorageEntityTransfers(
        MerchantCriteriaTransfer $merchantCriteriaTransfer
    ): ObjectCollection {
        $merchantStorageQuery = $this->getFactory()->createMerchantStorageQuery();

        if ($merchantCriteriaTransfer->getMerchantIds()) {
            $merchantStorageQuery->filterByIdMerchant_In($merchantCriteriaTransfer->getMerchantIds());
        }

        if ($merchantCriteriaTransfer->getFilter()) {
            $merchantStorageQuery = $this->buildQueryFromCriteria(
                $merchantStorageQuery,
                $merchantCriteriaTransfer->getFilter()
            )->setFormatter(ObjectFormatter::class);
        }

        return $merchantStorageQuery->find();
    }
}
