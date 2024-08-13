<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantApp\Persistence;

use Generated\Shared\Transfer\MerchantAppOnboardingCollectionTransfer;
use Generated\Shared\Transfer\MerchantAppOnboardingCriteriaTransfer;
use Generated\Shared\Transfer\MerchantAppOnboardingStatusCollectionTransfer;
use Orm\Zed\MerchantApp\Persistence\Map\SpyMerchantAppOnboardingStatusTableMap;
use Orm\Zed\MerchantApp\Persistence\SpyMerchantAppOnboardingQuery;
use Orm\Zed\MerchantApp\Persistence\SpyMerchantAppOnboardingStatusQuery;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\MerchantApp\Persistence\MerchantAppPersistenceFactory getFactory()
 */
class MerchantAppRepository extends AbstractRepository implements MerchantAppRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantAppOnboardingCriteriaTransfer $merchantAppOnboardingStatusCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantAppOnboardingStatusCollectionTransfer
     */
    public function getMerchantAppOnboardingStatusCollection(
        MerchantAppOnboardingCriteriaTransfer $merchantAppOnboardingStatusCriteriaTransfer
    ): MerchantAppOnboardingStatusCollectionTransfer {
        $merchantAppOnboardingStatusQuery = $this->getFactory()->createMerchantAppOnboardingStatusQuery();
        $merchantAppOnboardingStatusQuery = $this->applyStatusCriteria($merchantAppOnboardingStatusQuery, $merchantAppOnboardingStatusCriteriaTransfer);

        $merchantAppOnboardingStatusEntityCollection = $merchantAppOnboardingStatusQuery->find();

        return $this->getFactory()->createMerchantAppOnboardingStatusMapper()->mapMerchantAppOnboardingEntityCollectionToMerchantAppOnboardingTransferCollection(
            $merchantAppOnboardingStatusEntityCollection,
            new MerchantAppOnboardingStatusCollectionTransfer(),
        );
    }

    /**
     * @param \Orm\Zed\MerchantApp\Persistence\SpyMerchantAppOnboardingStatusQuery $merchantAppOnboardingStatusQuery
     * @param \Generated\Shared\Transfer\MerchantAppOnboardingCriteriaTransfer $merchantAppOnboardingCriteriaTransfer
     *
     * @return \Orm\Zed\MerchantApp\Persistence\SpyMerchantAppOnboardingStatusQuery
     */
    protected function applyStatusCriteria(
        SpyMerchantAppOnboardingStatusQuery $merchantAppOnboardingStatusQuery,
        MerchantAppOnboardingCriteriaTransfer $merchantAppOnboardingCriteriaTransfer
    ): SpyMerchantAppOnboardingStatusQuery {
        if ($merchantAppOnboardingCriteriaTransfer->getMerchant()) {
            $merchantAppOnboardingStatusQuery->filterByMerchantReference($merchantAppOnboardingCriteriaTransfer->getMerchant()->getMerchantReferenceOrFail());
        }

        if ($merchantAppOnboardingCriteriaTransfer->getAppIdentifiers() !== []) {
            $merchantAppOnboardingStatusQuery
                ->joinWithSpyMerchantAppOnboarding()
                ->useSpyMerchantAppOnboardingQuery()
                    ->filterByAppIdentifier_In($merchantAppOnboardingCriteriaTransfer->getAppIdentifiers())
                ->endUse();
        }

        if ($merchantAppOnboardingCriteriaTransfer->getType()) {
            $merchantAppOnboardingStatusQuery->joinWithSpyMerchantAppOnboarding();
            $merchantAppOnboardingStatusQuery
                ->useSpyMerchantAppOnboardingQuery()
                    ->filterByType($merchantAppOnboardingCriteriaTransfer->getType())
                ->endUse();
        }

        return $merchantAppOnboardingStatusQuery;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantAppOnboardingCriteriaTransfer $merchantAppOnboardingStatusCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantAppOnboardingCollectionTransfer
     */
    public function getMerchantAppOnboardingCollection(
        MerchantAppOnboardingCriteriaTransfer $merchantAppOnboardingStatusCriteriaTransfer
    ): MerchantAppOnboardingCollectionTransfer {
        $merchantAppOnboardingQuery = $this->getFactory()->createMerchantAppOnboardingQuery();
        $merchantAppOnboardingQuery
            ->joinWithSpyAppConfig()
            ->useSpyAppConfigQuery()
                ->filterByIsActive(true)
            ->endUse();

        $merchantAppOnboardingQuery = $this->applyOnboardingCriteria($merchantAppOnboardingQuery, $merchantAppOnboardingStatusCriteriaTransfer);

        $merchantAppOnboardingEntityCollection = $merchantAppOnboardingQuery->find();

        return $this->getFactory()->createMerchantAppOnboardingMapper()->mapMerchantAppOnboardingEntityCollectionToMerchantAppOnboardingCollectionTransfer(
            $merchantAppOnboardingEntityCollection,
            new MerchantAppOnboardingCollectionTransfer(),
        );
    }

    /**
     * @param \Orm\Zed\MerchantApp\Persistence\SpyMerchantAppOnboardingQuery $merchantAppOnboardingQuery
     * @param \Generated\Shared\Transfer\MerchantAppOnboardingCriteriaTransfer $merchantAppOnboardingCriteriaTransfer
     *
     * @return \Orm\Zed\MerchantApp\Persistence\SpyMerchantAppOnboardingQuery
     */
    protected function applyOnboardingCriteria(
        SpyMerchantAppOnboardingQuery $merchantAppOnboardingQuery,
        MerchantAppOnboardingCriteriaTransfer $merchantAppOnboardingCriteriaTransfer
    ): SpyMerchantAppOnboardingQuery {
        // Apply filter for the type of onboarding e.g. payment.
        if ($merchantAppOnboardingCriteriaTransfer->getType()) {
            $merchantAppOnboardingQuery->filterByType($merchantAppOnboardingCriteriaTransfer->getType());
        }

        // Apply filter for the app identifier.
        if ($merchantAppOnboardingCriteriaTransfer->getAppIdentifiers() !== []) {
            $merchantAppOnboardingQuery->filterByAppIdentifier_In($merchantAppOnboardingCriteriaTransfer->getAppIdentifiers());
        }

        // Apply filter for the merchant.
        if ($merchantAppOnboardingCriteriaTransfer->getMerchant()) {
            $merchantAppOnboardingQuery
                ->join('SpyMerchantAppOnboardingStatus', 'LEFT JOIN')
                ->addJoinCondition('SpyMerchantAppOnboardingStatus', 'spy_merchant_app_onboarding_status.merchant_reference = ?', $merchantAppOnboardingCriteriaTransfer->getMerchant()->getMerchantReferenceOrFail())
                ->withColumn(SpyMerchantAppOnboardingStatusTableMap::COL_STATUS, 'status');
        }

        return $merchantAppOnboardingQuery;
    }
}
