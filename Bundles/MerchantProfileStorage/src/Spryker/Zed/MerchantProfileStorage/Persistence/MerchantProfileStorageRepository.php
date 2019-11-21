<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileStorage\Persistence;

use Generated\Shared\Transfer\MerchantProfileCriteriaFilterTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\MerchantProfileStorage\Persistence\MerchantProfileStoragePersistenceFactory getFactory()
 */
class MerchantProfileStorageRepository extends AbstractRepository implements MerchantProfileStorageRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantProfileCriteriaFilterTransfer $merchantProfileCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\SpyMerchantProfileStorageEntityTransfer[]
     */
    public function getFilteredMerchantProfileStorageEntityTransfers(MerchantProfileCriteriaFilterTransfer $merchantProfileCriteriaFilterTransfer): array
    {
        $merchantProfileStorageQuery = $this->getFactory()
            ->createMerchantProfileStorageQuery();

        if ($merchantProfileCriteriaFilterTransfer->getMerchantIds()) {
            $merchantProfileStorageQuery->filterByFkMerchant_In($merchantProfileCriteriaFilterTransfer->getMerchantIds());
        }

        if ($merchantProfileCriteriaFilterTransfer->getFilter()) {
            $merchantProfileStorageQuery = $this->buildQueryFromCriteria(
                $merchantProfileStorageQuery,
                $merchantProfileCriteriaFilterTransfer->getFilter()
            );
        }

        return $merchantProfileStorageQuery->find();
    }
}
