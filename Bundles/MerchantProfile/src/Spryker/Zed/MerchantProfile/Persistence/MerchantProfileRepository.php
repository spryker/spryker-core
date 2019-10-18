<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfile\Persistence;

use Generated\Shared\Transfer\MerchantProfileCriteriaFilterTransfer;
use Generated\Shared\Transfer\MerchantProfileTransfer;
use Orm\Zed\MerchantProfile\Persistence\SpyMerchantProfileQuery;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\MerchantProfile\Persistence\MerchantProfilePersistenceFactory getFactory()
 */
class MerchantProfileRepository extends AbstractRepository implements MerchantProfileRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantProfileCriteriaFilterTransfer $merchantProfileCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileTransfer|null
     */
    public function findOne(MerchantProfileCriteriaFilterTransfer $merchantProfileCriteriaFilterTransfer): ?MerchantProfileTransfer
    {
        $merchantEntity = $this->applyFilters(
            $this->getFactory()->createMerchantProfileQuery(),
            $merchantProfileCriteriaFilterTransfer
        )->findOne();

        if (!$merchantEntity) {
            return null;
        }

        return $this->getFactory()
            ->createPropelMerchantProfileMapper()
            ->mapMerchantProfileEntityToMerchantProfileTransfer($merchantEntity, new MerchantProfileTransfer());
    }

    /**
     * @param \Orm\Zed\MerchantProfile\Persistence\SpyMerchantProfileQuery $merchantProfileQuery
     * @param \Generated\Shared\Transfer\MerchantProfileCriteriaFilterTransfer $merchantProfileCriteriaFilterTransfer
     *
     * @return \Orm\Zed\MerchantProfile\Persistence\SpyMerchantProfileQuery
     */
    protected function applyFilters(
        SpyMerchantProfileQuery $merchantProfileQuery,
        MerchantProfileCriteriaFilterTransfer $merchantProfileCriteriaFilterTransfer
    ): SpyMerchantProfileQuery {
        if ($merchantProfileCriteriaFilterTransfer->getFkMerchant() !== null) {
            $merchantProfileQuery->filterByFkMerchant($merchantProfileCriteriaFilterTransfer->getFkMerchant());
        }

        return $merchantProfileQuery;
    }
}
