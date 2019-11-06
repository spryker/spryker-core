<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
     * @return \Orm\Zed\MerchantProfileStorage\Persistence\SpyMerchantProfileStorage[]
     */
    public function getFilteredMerchantProfileStorageEntities(MerchantProfileCriteriaFilterTransfer $merchantProfileCriteriaFilterTransfer): array
    {
        $merchantProfileStorageQuery = $this->getFactory()
            ->createMerchantProfileStorageQuery();

        if ($merchantProfileCriteriaFilterTransfer->getMerchantIds()) {
            $merchantProfileStorageQuery->filterByFkMerchant_In($merchantProfileCriteriaFilterTransfer->getMerchantIds());
        }

        if ($merchantProfileCriteriaFilterTransfer->getFilter()) {
            $merchantProfileStorageQuery->setLimit(
                $merchantProfileCriteriaFilterTransfer->getFilter()->getLimit()
            );

            $merchantProfileStorageQuery->setOffset(
                $merchantProfileCriteriaFilterTransfer->getFilter()->getOffset()
            );
        }

        return $merchantProfileStorageQuery->find()
            ->getData();
    }
}
