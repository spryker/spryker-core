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
use Orm\Zed\Merchant\Persistence\Map\SpyMerchantTableMap;
use Orm\Zed\MerchantProfile\Persistence\SpyMerchantProfileQuery;
use Propel\Runtime\ActiveQuery\Criteria as PropelCriteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\MerchantProfile\Persistence\MerchantProfilePersistenceFactory getFactory()
 */
class MerchantProfileRepository extends AbstractRepository implements MerchantProfileRepositoryInterface
{
    /**
     * @var string
     */
    protected const COL_MERCHANT_REFERENCE = 'merchant_reference';

    /**
     * @param \Generated\Shared\Transfer\MerchantProfileCriteriaTransfer $merchantProfileCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileTransfer|null
     */
    public function findOne(MerchantProfileCriteriaTransfer $merchantProfileCriteriaTransfer): ?MerchantProfileTransfer
    {
        $merchantProfileEntity = $this->applyFilters(
            $this->getFactory()->createMerchantProfileQuery(),
            $merchantProfileCriteriaTransfer,
        )->findOne();

        if (!$merchantProfileEntity) {
            return null;
        }

        return $this->getFactory()
            ->createPropelMerchantProfileMapper()
            ->mapMerchantProfileEntityToMerchantProfileTransfer($merchantProfileEntity, new MerchantProfileTransfer());
    }

    /**
     * @param array $merchantReferences
     *
     * @return array
     */
    public function findMerchantProfileAddressesCollectionIndexedByMerchantReference(array $merchantReferences): array
    {
        $merchantProfileQueryAddress = $this->getFactory()
            ->createMerchantProfileAddressQuery()
            ->useSpyMerchantProfileQuery()
                ->useSpyMerchantQuery()
                    ->withColumn(SpyMerchantTableMap::COL_MERCHANT_REFERENCE, static::COL_MERCHANT_REFERENCE)
                    ->filterByMerchantReference(
                        $merchantReferences,
                        PropelCriteria::IN,
                    )
                ->endUse()
            ->endUse();

        $merchantProfileAddressEntityCollection = $merchantProfileQueryAddress->find();

        return $this->getFactory()
            ->createMerchantProfileAddressMapper()
            ->mapMerchantProfileAddressEntityCollectionToMerchantProfileAddressTransfersIndexedByMerchantReference(
                $merchantProfileAddressEntityCollection,
                [],
            );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProfileCriteriaTransfer $merchantProfileCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileCollectionTransfer
     */
    public function get(MerchantProfileCriteriaTransfer $merchantProfileCriteriaTransfer): MerchantProfileCollectionTransfer
    {
        /** @var \Orm\Zed\MerchantProfile\Persistence\SpyMerchantProfileQuery $merchantProfileQuery */
        $merchantProfileQuery = $this->getFactory()
            ->createMerchantProfileQuery()
            ->joinWithSpyMerchant()
            ->leftJoinWithSpyMerchantProfileAddress()
            ->useSpyMerchantProfileAddressQuery(null, Criteria::LEFT_JOIN)
                ->leftJoinWithSpyCountry()
            ->endUse();

        $merchantProfileQuery = $this->applyFilters($merchantProfileQuery, $merchantProfileCriteriaTransfer);
        $merchantProfileQuery = $this->buildQueryFromCriteria($merchantProfileQuery, $merchantProfileCriteriaTransfer->getFilter());
        $merchantProfileEntityCollection = $merchantProfileQuery->find();

        return $this->getFactory()
            ->createPropelMerchantProfileMapper()
            ->mapMerchantProfileEntityCollectionToMerchantProfileCollectionTransfer($merchantProfileEntityCollection);
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $modelCriteria
     * @param \Generated\Shared\Transfer\FilterTransfer|null $filterTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function buildQueryFromCriteria(ModelCriteria $modelCriteria, ?FilterTransfer $filterTransfer = null): ModelCriteria
    {
        $modelCriteria = parent::buildQueryFromCriteria($modelCriteria, $filterTransfer);
        $modelCriteria->setFormatter(ModelCriteria::FORMAT_OBJECT);

        return $modelCriteria;
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

        if ($merchantProfileCriteriaTransfer->getMerchantReferences()) {
            $merchantProfileQuery->useSpyMerchantQuery()
                ->filterByMerchantReference(
                    $merchantProfileCriteriaTransfer->getMerchantReferences(),
                    PropelCriteria::IN,
                )
                ->endUse();
        }

        return $merchantProfileQuery;
    }
}
