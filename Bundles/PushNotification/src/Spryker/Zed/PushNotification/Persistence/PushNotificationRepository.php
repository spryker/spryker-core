<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotification\Persistence;

use ArrayObject;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\PushNotificationCollectionTransfer;
use Generated\Shared\Transfer\PushNotificationCriteriaTransfer;
use Generated\Shared\Transfer\PushNotificationGroupCollectionTransfer;
use Generated\Shared\Transfer\PushNotificationGroupConditionsTransfer;
use Generated\Shared\Transfer\PushNotificationGroupCriteriaTransfer;
use Generated\Shared\Transfer\PushNotificationProviderCollectionTransfer;
use Generated\Shared\Transfer\PushNotificationProviderConditionsTransfer;
use Generated\Shared\Transfer\PushNotificationProviderCriteriaTransfer;
use Generated\Shared\Transfer\PushNotificationSubscriptionCollectionTransfer;
use Generated\Shared\Transfer\PushNotificationSubscriptionConditionsTransfer;
use Generated\Shared\Transfer\PushNotificationSubscriptionCriteriaTransfer;
use Orm\Zed\PushNotification\Persistence\Map\SpyPushNotificationSubscriptionDeliveryLogTableMap;
use Orm\Zed\PushNotification\Persistence\Map\SpyPushNotificationSubscriptionTableMap;
use Orm\Zed\PushNotification\Persistence\Map\SpyPushNotificationTableMap;
use Orm\Zed\PushNotification\Persistence\SpyPushNotificationGroupQuery;
use Orm\Zed\PushNotification\Persistence\SpyPushNotificationProviderQuery;
use Orm\Zed\PushNotification\Persistence\SpyPushNotificationQuery;
use Orm\Zed\PushNotification\Persistence\SpyPushNotificationSubscriptionQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\Join;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\PushNotification\Persistence\PushNotificationPersistenceFactory getFactory()
 */
class PushNotificationRepository extends AbstractRepository implements PushNotificationRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\PushNotificationCriteriaTransfer $pushNotificationCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationCollectionTransfer
     */
    public function getPushNotificationCollection(
        PushNotificationCriteriaTransfer $pushNotificationCriteriaTransfer
    ): PushNotificationCollectionTransfer {
        $pushNotificationCollectionTransfer = new PushNotificationCollectionTransfer();

        $pushNotificationQuery = $this->getFactory()
            ->createPushNotificationQuery()
            ->joinWithSpyPushNotificationProvider();

        $pushNotificationQuery = $this->applyPushNotificationFilters($pushNotificationQuery, $pushNotificationCriteriaTransfer);
        $pushNotificationQuery = $this->applySorting($pushNotificationQuery, $pushNotificationCriteriaTransfer->getSortCollection());

        /** @var \Orm\Zed\PushNotification\Persistence\SpyPushNotificationQuery $pushNotificationQuery */
        $pushNotificationQuery = $pushNotificationQuery->distinct();

        $paginationTransfer = $pushNotificationCriteriaTransfer->getPagination();
        if ($paginationTransfer !== null) {
            $pushNotificationQuery = $this->applyPagination($pushNotificationQuery, $paginationTransfer);
            $pushNotificationCollectionTransfer->setPagination($paginationTransfer);
        }

        $pushNotificationEntityCollection = $pushNotificationQuery->find();

        $pushNotificationConditionsTransfer = $pushNotificationCriteriaTransfer->getPushNotificationConditions();
        if ($pushNotificationConditionsTransfer && $pushNotificationConditionsTransfer->getNotificationSent() === false) {
            $pushNotificationEntityCollection = $this->extendPushNotificationsWithNotDeliveredSubscriptions(
                $pushNotificationEntityCollection,
            );
        }

        return $this
            ->getFactory()
            ->createPushNotificationMapper()
            ->mapPushNotificationEntityCollectionToPushNotificationCollectionTransfer(
                $pushNotificationEntityCollection,
                $pushNotificationCollectionTransfer,
            );
    }

    /**
     * @param \Generated\Shared\Transfer\PushNotificationProviderCriteriaTransfer $pushNotificationProviderCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationProviderCollectionTransfer
     */
    public function getPushNotificationProviderCollection(
        PushNotificationProviderCriteriaTransfer $pushNotificationProviderCriteriaTransfer
    ): PushNotificationProviderCollectionTransfer {
        $pushNotificationProviderCollectionTransfer = new PushNotificationProviderCollectionTransfer();

        $pushNotificationProviderQuery = $this->getFactory()->createPushNotificationProviderQuery();
        if ($pushNotificationProviderCriteriaTransfer->getPushNotificationProviderConditions()) {
            $pushNotificationProviderQuery = $this->applyPushNotificationProviderFilters(
                $pushNotificationProviderQuery,
                $pushNotificationProviderCriteriaTransfer->getPushNotificationProviderConditionsOrFail(),
            );
        }
        $paginationTransfer = $pushNotificationProviderCriteriaTransfer->getPagination();
        if ($paginationTransfer !== null) {
            $pushNotificationProviderQuery = $this->applyPagination(
                $pushNotificationProviderQuery,
                $paginationTransfer,
            );
            $pushNotificationProviderCollectionTransfer->setPagination($paginationTransfer);
        }

        $pushNotificationProviderQuery = $this->applySorting(
            $pushNotificationProviderQuery,
            $pushNotificationProviderCriteriaTransfer->getSortCollection(),
        );

        /**
         * @var \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\PushNotification\Persistence\SpyPushNotificationProvider> $pushNotificationProviderEntities
         */
        $pushNotificationProviderEntities = $pushNotificationProviderQuery->find();

        return $this->getFactory()
            ->createPushNotificationProviderMapper()
            ->mapPushNotificationProviderEntitiesToPushNotificationProviderCollectionTransfer(
                $pushNotificationProviderEntities,
                $pushNotificationProviderCollectionTransfer,
            );
    }

    /**
     * @param \Generated\Shared\Transfer\PushNotificationGroupCriteriaTransfer $pushNotificationGroupCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationGroupCollectionTransfer
     */
    public function getPushNotificationGroupCollection(
        PushNotificationGroupCriteriaTransfer $pushNotificationGroupCriteriaTransfer
    ): PushNotificationGroupCollectionTransfer {
        $pushNotificationGroupCollectionTransfer = new PushNotificationGroupCollectionTransfer();

        $pushNotificationGroupQuery = $this->getFactory()->createPushNotificationGroupQuery();
        if ($pushNotificationGroupCriteriaTransfer->getPushNotificationGroupConditions()) {
            $pushNotificationGroupQuery = $this->applyPushNotificationGroupFilters(
                $pushNotificationGroupQuery,
                $pushNotificationGroupCriteriaTransfer->getPushNotificationGroupConditions(),
            );
        }

        $paginationTransfer = $pushNotificationGroupCriteriaTransfer->getPagination();
        if ($paginationTransfer !== null) {
            $pushNotificationGroupQuery = $this->applyPagination(
                $pushNotificationGroupQuery,
                $paginationTransfer,
            );
            $pushNotificationGroupCollectionTransfer->setPagination($paginationTransfer);
        }

        $pushNotificationGroupQuery = $this->applySorting(
            $pushNotificationGroupQuery,
            $pushNotificationGroupCriteriaTransfer->getSortCollection(),
        );

        return $this->getFactory()
            ->createPushNotificationGroupMapper()
            ->mapPushNotificationGroupEntitiesToPushNotificationGroupCollectionTransfer(
                $pushNotificationGroupQuery->find(),
                $pushNotificationGroupCollectionTransfer,
            );
    }

    /**
     * @param \Generated\Shared\Transfer\PushNotificationSubscriptionCriteriaTransfer $pushNotificationSubscriptionCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationSubscriptionCollectionTransfer
     */
    public function getPushNotificationSubscriptionCollection(
        PushNotificationSubscriptionCriteriaTransfer $pushNotificationSubscriptionCriteriaTransfer
    ): PushNotificationSubscriptionCollectionTransfer {
        $pushNotificationSubscriptionCollectionTransfer = new PushNotificationSubscriptionCollectionTransfer();
        $pushNotificationSubscriptionQuery = $this->getFactory()->createPushNotificationSubscriptionQuery();
        if ($pushNotificationSubscriptionCriteriaTransfer->getPushNotificationSubscriptionConditions()) {
            $pushNotificationSubscriptionQuery = $this->applyPushNotificationSubscriptionFilters(
                $pushNotificationSubscriptionQuery,
                $pushNotificationSubscriptionCriteriaTransfer->getPushNotificationSubscriptionConditionsOrFail(),
            );
        }

        $paginationTransfer = $pushNotificationSubscriptionCriteriaTransfer->getPagination();
        if ($paginationTransfer !== null) {
            $pushNotificationSubscriptionQuery = $this->applyPagination($pushNotificationSubscriptionQuery, $paginationTransfer);
            $pushNotificationSubscriptionCollectionTransfer->setPagination($paginationTransfer);
        }

        return $this->getFactory()
            ->createPushNotificationSubscriptionMapper()
            ->mapPushNotificationSubscriptionEntitiesToPushNotificationSubscriptionCollectionTransfer(
                $pushNotificationSubscriptionQuery->find(),
                $pushNotificationSubscriptionCollectionTransfer,
            );
    }

    /**
     * @param \Orm\Zed\PushNotification\Persistence\SpyPushNotificationGroupQuery $pushNotificationGroupQuery
     * @param \Generated\Shared\Transfer\PushNotificationGroupConditionsTransfer $pushNotificationGroupConditionsTransfer
     *
     * @return \Orm\Zed\PushNotification\Persistence\SpyPushNotificationGroupQuery
     */
    protected function applyPushNotificationGroupFilters(
        SpyPushNotificationGroupQuery $pushNotificationGroupQuery,
        PushNotificationGroupConditionsTransfer $pushNotificationGroupConditionsTransfer
    ): SpyPushNotificationGroupQuery {
        if ($pushNotificationGroupConditionsTransfer->getNames()) {
            $pushNotificationGroupQuery->filterByName_In(
                $pushNotificationGroupConditionsTransfer->getNames(),
            );
        }
        if ($pushNotificationGroupConditionsTransfer->getIdentifiers()) {
            $pushNotificationGroupQuery->filterByIdentifier_In(
                $pushNotificationGroupConditionsTransfer->getIdentifiers(),
            );
        }

        return $pushNotificationGroupQuery;
    }

    /**
     * @param \Orm\Zed\PushNotification\Persistence\SpyPushNotificationProviderQuery $pushNotificationProviderQuery
     * @param \Generated\Shared\Transfer\PushNotificationProviderConditionsTransfer $pushNotificationProviderConditionsTransfer
     *
     * @return \Orm\Zed\PushNotification\Persistence\SpyPushNotificationProviderQuery
     */
    protected function applyPushNotificationProviderFilters(
        SpyPushNotificationProviderQuery $pushNotificationProviderQuery,
        PushNotificationProviderConditionsTransfer $pushNotificationProviderConditionsTransfer
    ): SpyPushNotificationProviderQuery {
        if ($pushNotificationProviderConditionsTransfer->getNames()) {
            $pushNotificationProviderQuery->filterByName_In(
                $pushNotificationProviderConditionsTransfer->getNames(),
            );
        }

        return $pushNotificationProviderQuery;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \ArrayObject<int, \Generated\Shared\Transfer\SortTransfer> $sortTransfers
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function applySorting(
        ModelCriteria $query,
        ArrayObject $sortTransfers
    ): ModelCriteria {
        foreach ($sortTransfers as $sortTransfer) {
            $query->orderBy(
                $sortTransfer->getFieldOrFail(),
                $sortTransfer->getIsAscending() ? Criteria::ASC : Criteria::DESC,
            );
        }

        return $query;
    }

    /**
     * @param \Orm\Zed\PushNotification\Persistence\SpyPushNotificationQuery $pushNotificationQuery
     * @param \Generated\Shared\Transfer\PushNotificationCriteriaTransfer $pushNotificationCriteriaTransfer
     *
     * @return \Orm\Zed\PushNotification\Persistence\SpyPushNotificationQuery
     */
    protected function applyPushNotificationFilters(
        SpyPushNotificationQuery $pushNotificationQuery,
        PushNotificationCriteriaTransfer $pushNotificationCriteriaTransfer
    ): SpyPushNotificationQuery {
        $pushNotificationConditionsTransfer = $pushNotificationCriteriaTransfer->getPushNotificationConditions();
        if ($pushNotificationConditionsTransfer === null) {
            return $pushNotificationQuery;
        }

        if ($pushNotificationConditionsTransfer->getPushNotificationIds()) {
            $pushNotificationQuery->filterByIdPushNotification(
                $pushNotificationConditionsTransfer->getPushNotificationIds(),
                Criteria::IN,
            );
        }

        if ($pushNotificationConditionsTransfer->getUuids()) {
            $pushNotificationQuery->filterByUuid(
                $pushNotificationConditionsTransfer->getUuids(),
                Criteria::IN,
            );
        }

        if ($pushNotificationConditionsTransfer->getNotificationSent() === null) {
            return $pushNotificationQuery;
        }

        if ($pushNotificationConditionsTransfer->getNotificationSent() === false) {
            $pushNotificationQuery
                ->useSpyPushNotificationSubscriptionDeliveryLogQuery(null, Criteria::LEFT_JOIN)
                ->filterByIdPushNotificationSubscriptionDeliveryLog(null, Criteria::ISNULL)
                ->endUse();

            return $pushNotificationQuery;
        }

        $pushNotificationQuery
            ->useSpyPushNotificationSubscriptionDeliveryLogQuery(null, Criteria::LEFT_JOIN)
            ->filterByIdPushNotificationSubscriptionDeliveryLog(null, Criteria::ISNOTNULL);

        return $pushNotificationQuery;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function applyPagination(ModelCriteria $query, PaginationTransfer $paginationTransfer): ModelCriteria
    {
        if ($paginationTransfer->getOffset() !== null && $paginationTransfer->getLimit() !== null) {
            $query
                ->setOffset($paginationTransfer->getOffsetOrFail())
                ->setLimit($paginationTransfer->getLimitOrFail());

            return $query;
        }
        $paginationModel = $query->paginate(
            $paginationTransfer->getPageOrFail(),
            $paginationTransfer->getMaxPerPageOrFail(),
        );

        $this->getFactory()->createPaginationMapper()->mapPropelModelPagerToPaginationTransfer(
            $paginationModel,
            $paginationTransfer,
        );

        return $paginationModel->getQuery();
    }

    /**
     * @param \Orm\Zed\PushNotification\Persistence\SpyPushNotificationSubscriptionQuery $pushNotificationSubscriptionQuery
     * @param \Generated\Shared\Transfer\PushNotificationSubscriptionConditionsTransfer $pushNotificationSubscriptionConditionsTransfer
     *
     * @return \Orm\Zed\PushNotification\Persistence\SpyPushNotificationSubscriptionQuery
     */
    protected function applyPushNotificationSubscriptionFilters(
        SpyPushNotificationSubscriptionQuery $pushNotificationSubscriptionQuery,
        PushNotificationSubscriptionConditionsTransfer $pushNotificationSubscriptionConditionsTransfer
    ): SpyPushNotificationSubscriptionQuery {
        if ($pushNotificationSubscriptionConditionsTransfer->getExpiredAt()) {
            $pushNotificationSubscriptionQuery->filterByExpiredAt(
                $pushNotificationSubscriptionConditionsTransfer->getExpiredAt(),
                Criteria::LESS_EQUAL,
            );
        }
        if ($pushNotificationSubscriptionConditionsTransfer->getPushNotificationGroupIds()) {
            $pushNotificationSubscriptionQuery->filterByFkPushNotificationGroup_In(
                $pushNotificationSubscriptionConditionsTransfer->getPushNotificationGroupIds(),
            );
        }
        if ($pushNotificationSubscriptionConditionsTransfer->getPushNotificationProviderIds()) {
            $pushNotificationSubscriptionQuery->filterByFkPushNotificationProvider_In(
                $pushNotificationSubscriptionConditionsTransfer->getPushNotificationProviderIds(),
            );
        }
        if ($pushNotificationSubscriptionConditionsTransfer->getPayloadCheckSums()) {
            $pushNotificationSubscriptionQuery->filterByPayloadChecksum_In(
                $pushNotificationSubscriptionConditionsTransfer->getPayloadCheckSums(),
            );
        }

        return $pushNotificationSubscriptionQuery;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\PushNotification\Persistence\SpyPushNotification> $pushNotificationEntityCollection
     *
     * @return \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\PushNotification\Persistence\SpyPushNotification>
     */
    protected function extendPushNotificationsWithNotDeliveredSubscriptions(
        ObjectCollection $pushNotificationEntityCollection
    ): ObjectCollection {
        $pushNotificationAlias = SpyPushNotificationTableMap::getTableMap()->getPhpNameOrFail();
        $pushNotificationSubscriptionDeliveryLogAlias = SpyPushNotificationSubscriptionDeliveryLogTableMap::getTableMap()
            ->getPhpNameOrFail();

        $pushNotificationJoin = new Join(
            [
                SpyPushNotificationSubscriptionTableMap::COL_FK_PUSH_NOTIFICATION_GROUP,
                SpyPushNotificationSubscriptionTableMap::COL_FK_PUSH_NOTIFICATION_PROVIDER,
            ],
            [
                SpyPushNotificationTableMap::COL_FK_PUSH_NOTIFICATION_GROUP,
                SpyPushNotificationTableMap::COL_FK_PUSH_NOTIFICATION_PROVIDER,
            ],
        );

        $pushNotificationSubscriptionQuery = $this->getFactory()
            ->createPushNotificationSubscriptionQuery()
            ->distinct()
            ->addJoinObject($pushNotificationJoin, $pushNotificationAlias)
            ->leftJoinSpyPushNotificationSubscriptionDeliveryLog()
            ->addJoinCondition(
                $pushNotificationSubscriptionDeliveryLogAlias,
                sprintf(
                    '%s=%s',
                    SpyPushNotificationSubscriptionDeliveryLogTableMap::COL_FK_PUSH_NOTIFICATION,
                    SpyPushNotificationTableMap::COL_ID_PUSH_NOTIFICATION,
                ),
            )
            ->add(
                SpyPushNotificationTableMap::COL_ID_PUSH_NOTIFICATION,
                $this->extractPushNotificationIds($pushNotificationEntityCollection),
                Criteria::IN,
            )
            ->add(
                SpyPushNotificationSubscriptionDeliveryLogTableMap::COL_ID_PUSH_NOTIFICATION_SUBSCRIPTION_DELIVERY_LOG,
                null,
                Criteria::ISNULL,
            );

        $pushNotificationSubscriptionEntitiesGroupedByProviderAndGroup = $this->groupPushNotificationProviderEntitiesByProviderAndGroup(
            $pushNotificationSubscriptionQuery->find(),
        );
        /** @var \Orm\Zed\PushNotification\Persistence\SpyPushNotification $pushNotificationEntity */
        foreach ($pushNotificationEntityCollection as $pushNotificationEntity) {
            $idProvider = $pushNotificationEntity->getFkPushNotificationProvider();
            $idGroup = $pushNotificationEntity->getFkPushNotificationGroup();
            $pushNotificationSubscriptions = $pushNotificationSubscriptionEntitiesGroupedByProviderAndGroup[$idProvider][$idGroup] ?? [];

            $pushNotificationEntity->setPushNotificationSubscriptions(new ArrayObject($pushNotificationSubscriptions));
        }

        return $pushNotificationEntityCollection;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\PushNotification\Persistence\SpyPushNotification> $pushNotificationEntityCollection
     *
     * @return array<int>
     */
    protected function extractPushNotificationIds(ObjectCollection $pushNotificationEntityCollection): array
    {
        $pushNotificationIds = [];
        foreach ($pushNotificationEntityCollection as $pushNotificationEntity) {
            $pushNotificationIds[] = $pushNotificationEntity->getIdPushNotification();
        }

        return $pushNotificationIds;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\PushNotification\Persistence\SpyPushNotificationSubscription> $pushNotificationSubscriptionCollection
     *
     * @return array<int, array<int, array<int, \Orm\Zed\PushNotification\Persistence\SpyPushNotificationSubscription>>>
     */
    protected function groupPushNotificationProviderEntitiesByProviderAndGroup(
        ObjectCollection $pushNotificationSubscriptionCollection
    ): array {
        $pushNotificationSubscriptionsGroupedByProviderAndGroup = [];
        /** @var \Orm\Zed\PushNotification\Persistence\SpyPushNotificationSubscription $pushNotificationEntity */
        foreach ($pushNotificationSubscriptionCollection as $pushNotificationEntity) {
            $idProvider = $pushNotificationEntity->getFkPushNotificationProvider();
            $idGroup = $pushNotificationEntity->getFkPushNotificationGroup();
            if (!isset($pushNotificationSubscriptionsGroupedByProviderAndGroup[$idProvider])) {
                $pushNotificationSubscriptionsGroupedByProviderAndGroup[$idProvider] = [];
            }
            if (!isset($pushNotificationSubscriptionsGroupedByProviderAndGroup[$idProvider][$idGroup])) {
                $pushNotificationSubscriptionsGroupedByProviderAndGroup[$idProvider][$idGroup] = [];
            }
            $pushNotificationSubscriptionsGroupedByProviderAndGroup[$idProvider][$idGroup][] = $pushNotificationEntity;
        }

        return $pushNotificationSubscriptionsGroupedByProviderAndGroup;
    }
}
