<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfile\Persistence;

use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\MerchantProfileCollectionTransfer;
use Generated\Shared\Transfer\MerchantProfileCriteriaTransfer;
use Generated\Shared\Transfer\MerchantProfileTransfer;
use Orm\Zed\MerchantProfile\Persistence\SpyMerchantProfileQuery;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\MerchantProfile\Persistence\MerchantProfilePersistenceFactory getFactory()
 */
class MerchantProfileRepository extends AbstractRepository implements MerchantProfileRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantProfileCriteriaTransfer $merchantProfileCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileTransfer|null
     */
    public function findOne(MerchantProfileCriteriaTransfer $merchantProfileCriteriaTransfer): ?MerchantProfileTransfer
    {
        $merchantProfileEntity = $this->applyFilters(
            $this->getFactory()->createMerchantProfileQuery(),
            $merchantProfileCriteriaTransfer
        )->findOne();

        if (!$merchantProfileEntity) {
            return null;
        }

        return $this->getFactory()
            ->createPropelMerchantProfileMapper()
            ->mapMerchantProfileEntityToMerchantProfileTransfer($merchantProfileEntity, new MerchantProfileTransfer());
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProfileCriteriaTransfer $merchantProfileCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileCollectionTransfer
     */
    public function get(MerchantProfileCriteriaTransfer $merchantProfileCriteriaTransfer): MerchantProfileCollectionTransfer
    {
        $merchantProfileCollectionTransfer = new MerchantProfileCollectionTransfer();
        $merchantProfileQuery = $this->getFactory()
            ->createMerchantProfileQuery()
            ->joinWithSpyMerchant()
            ->useSpyMerchantProfileAddressQuery(null, Criteria::LEFT_JOIN)
                ->leftJoinWithSpyCountry()
            ->endUse();

        $merchantProfileQuery = $this->applyFilters($merchantProfileQuery, $merchantProfileCriteriaTransfer);
        $merchantProfileQuery = $this->buildQueryFromCriteria($merchantProfileQuery, $merchantProfileCriteriaTransfer->getFilter());
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
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $criteria
     * @param \Generated\Shared\Transfer\FilterTransfer|null $filterTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function buildQueryFromCriteria(ModelCriteria $criteria, ?FilterTransfer $filterTransfer = null): ModelCriteria
    {
        $criteria = parent::buildQueryFromCriteria($criteria, $filterTransfer);
        $criteria->setFormatter(ModelCriteria::FORMAT_OBJECT);

        return $criteria;
    }

    /**
     * @param \Orm\Zed\MerchantProfile\Persistence\SpyMerchantProfileQuery $merchantProfileQuery
     * @param \Generated\Shared\Transfer\MerchantProfileCriteriaTransfer $merchantProfileCriteriaTransfer
     *
     * @return \Orm\Zed\MerchantProfile\Persistence\SpyMerchantProfileQuery
     */
    protected function applyFilters(
        SpyMerchantProfileQuery $merchantProfileQuery,
        MerchantProfileCriteriaTransfer $merchantProfileCriteriaTransfer
    ): SpyMerchantProfileQuery {
        if ($merchantProfileCriteriaTransfer->getFkMerchant() !== null) {
            $merchantProfileQuery->filterByFkMerchant($merchantProfileCriteriaTransfer->getFkMerchant());
        }

        if ($merchantProfileCriteriaTransfer->getMerchantIds()) {
            $merchantProfileQuery->filterByFkMerchant_In($merchantProfileCriteriaTransfer->getMerchantIds());
        }

        if ($merchantProfileCriteriaTransfer->getMerchantProfileIds()) {
            $merchantProfileQuery->filterByIdMerchantProfile_In($merchantProfileCriteriaTransfer->getMerchantProfileIds());
        }

        if ($merchantProfileCriteriaTransfer->getMerchantReference()) {
            $merchantProfileQuery->useSpyMerchantQuery()
                ->filterByMerchantReference($merchantProfileCriteriaTransfer->getMerchantReference())
                ->endUse();
        }

        if ($merchantProfileCriteriaTransfer->getIdMerchantProfile() !== null) {
            $merchantProfileQuery->filterByIdMerchantProfile($merchantProfileCriteriaTransfer->getIdMerchantProfile());
        }

        return $merchantProfileQuery;
    }
}
