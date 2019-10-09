<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfile\Persistence;

use Generated\Shared\Transfer\MerchantProfileCollectionTransfer;
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
        $merchantProfileQuery = $this->getFactory()->createMerchantProfileQuery()->leftJoinSpyUrl();
        $merchantProfileEntity = $this->applyFilters($merchantProfileQuery, $merchantProfileCriteriaFilterTransfer)->findOne();

        if (!$merchantProfileEntity) {
            return null;
        }

        return $this->getFactory()
            ->createPropelMerchantProfileMapper()
            ->mapMerchantProfileEntityToMerchantProfileTransfer($merchantProfileEntity, new MerchantProfileTransfer());
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProfileCriteriaFilterTransfer|null $merchantProfileCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileCollectionTransfer
     */
    public function find(?MerchantProfileCriteriaFilterTransfer $merchantProfileCriteriaFilterTransfer = null): MerchantProfileCollectionTransfer
    {
        $merchantProfileCollectionTransfer = new MerchantProfileCollectionTransfer();
        $merchantProfileQuery = $this->getFactory()
            ->createMerchantProfileQuery()
            ->joinSpyMerchant();
        if ($merchantProfileCriteriaFilterTransfer) {
            $merchantProfileQuery = $this->applyFilters($merchantProfileQuery, $merchantProfileCriteriaFilterTransfer);
        }
        $merchantProfileEntityCollection = $merchantProfileQuery->find();

        foreach ($merchantProfileEntityCollection as $merchantProfileEntity) {
            $merchantProfileTransfer = $this->getFactory()
                ->createPropelMerchantProfileMapper()
                ->mapMerchantProfileEntityToMerchantProfileTransfer($merchantProfileEntity, new MerchantProfileTransfer());

            $merchantProfileCollectionTransfer->addMerchantProfile($merchantProfileTransfer);
        }

        return $merchantProfileCollectionTransfer;
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
        if ($merchantProfileCriteriaFilterTransfer->getIdMerchant() !== null) {
            $merchantProfileQuery->filterByFkMerchant($merchantProfileCriteriaFilterTransfer->getIdMerchant());
        }

        if ($merchantProfileCriteriaFilterTransfer->getMerchantIds()) {
            $merchantProfileQuery->filterByFkMerchant_In($merchantProfileCriteriaFilterTransfer->getMerchantIds());
        }

        if ($merchantProfileCriteriaFilterTransfer->getMerchantProfileIds()) {
            $merchantProfileQuery->filterByIdMerchantProfile_In($merchantProfileCriteriaFilterTransfer->getMerchantProfileIds());
        }

        if ($merchantProfileCriteriaFilterTransfer->getIdMerchantProfile() !== null) {
            $merchantProfileQuery->filterByIdMerchantProfile($merchantProfileCriteriaFilterTransfer->getIdMerchantProfile());
        }

        if ($merchantProfileCriteriaFilterTransfer->getIsActive() !== null) {
            $merchantProfileQuery->filterByIsActive($merchantProfileCriteriaFilterTransfer->getIsActive());
        }

        return $merchantProfileQuery;
    }
}
