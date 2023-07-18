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
use Generated\Shared\Transfer\PushNotificationGroupCriteriaTransfer;
use Generated\Shared\Transfer\PushNotificationProviderCollectionTransfer;
use Generated\Shared\Transfer\PushNotificationProviderCriteriaTransfer;
use Generated\Shared\Transfer\PushNotificationSubscriptionCollectionTransfer;
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
        $pushNotificationQuery = $this->getFactory()->createPushNotificationQuery()->joinWithSpyPushNotificationProvider();
        $pushNotificationQuery = $this->applyPushNotificationFilters($pushNotificationQuery, $pushNotificationCriteriaTransfer);

        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\SortTransfer> $sortTransfers */
        $sortTransfers = $pushNotificationCriteriaTransfer->getSortCollection();
        $pushNotificationQuery = $this->applySorting($pushNotificationQuery, $sortTransfers);

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
        $pushNotificationProviderQuery = $this->applyPushNotificationProviderFilters(
            $pushNotificationProviderQuery,
            $pushNotificationProviderCriteriaTransfer,
        );

        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\SortTransfer> $sortTransfers */
        $sortTransfers = $pushNotificationProviderCriteriaTransfer->getSortCollection();
        $pushNotificationProviderQuery = $this->applySorting($pushNotificationProviderQuery, $sortTransfers);
        $paginationTransfer = $pushNotificationProviderCriteriaTransfer->getPagination();

        if ($paginationTransfer) {
            $pushNotificationProviderQuery = $this->applyPagination(
                $pushNotificationProviderQuery,
                $paginationTransfer,
            );

            $pushNotificationProviderCollectionTransfer->setPagination($paginationTransfer);
        }

        return $this->getFactory()
            ->createPushNotificationProviderMapper()
            ->mapPushNotificationProviderEntitiesToPushNotificationProviderCollectionTransfer(
                $pushNotificationProviderQuery->find(),
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
        $pushNotificationGroupQuery = $this->applyPushNotificationGroupFilters(
            $pushNotificationGroupQuery,
            $pushNotificationGroupCriteriaTransfer,
        );

        $paginationTransfer = $pushNotificationGroupCriteriaTransfer->getPagination();
        if ($paginationTransfer !== null) {
            $pushNotificationGroupQuery = $this->applyPagination(
                $pushNotificationGroupQuery,
                $paginationTransfer,
            );
            $pushNotificationGroupCollectionTransfer->setPagination($paginationTransfer);
        }

        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\SortTransfer> $sortTransfers */
        $sortTransfers = $pushNotificationGroupCriteriaTransfer->getSortCollection();
        $pushNotificationGroupQuery = $this->applySorting($pushNotificationGroupQuery, $sortTransfers);

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
        $pushNotificationSubscriptionQuery = $this->applyPushNotificationSubscriptionFilters(
            $pushNotificationSubscriptionQuery,
            $pushNotificationSubscriptionCriteriaTransfer,
        );

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
     * @param \Generated\Shared\Transfer\PushNotificationSubscriptionCriteriaTransfer $pushNotificationSubscriptionCriteriaTransfer
     *
     * @return bool
     */
    public function pushNotificationSubscriptionExists(
        PushNotificationSubscriptionCriteriaTransfer $pushNotificationSubscriptionCriteriaTransfer
    ): bool {
        $pushNotificationSubscriptionQuery = $this->getFactory()->createPushNotificationSubscriptionQuery();
        $pushNotificationSubscriptionQuery = $this->applyPushNotificationSubscriptionFilters(
            $pushNotificationSubscriptionQuery,
            $pushNotificationSubscriptionCriteriaTransfer,
        );

        return $pushNotificationSubscriptionQuery->exists();
    }

    /**
     * @param \Generated\Shared\Transfer\PushNotificationCriteriaTransfer $pushNotificationCriteriaTransfer
     *
     * @return bool
     */
    public function pushNotificationExists(
        PushNotificationCriteriaTransfer $pushNotificationCriteriaTransfer
    ): bool {
        $pushNotificationQuery = $this->getFactory()->createPushNotificationQuery();
        $pushNotificationQuery = $this->applyPushNotificationFilters(
            $pushNotificationQuery,
            $pushNotificationCriteriaTransfer,
        );

        return $pushNotificationQuery->exists();
    }

    /**
     * @param \Orm\Zed\PushNotification\Persistence\SpyPushNotificationGroupQuery $pushNotificationGroupQuery
     * @param \Generated\Shared\Transfer\PushNotificationGroupCriteriaTransfer $pushNotificationGroupCriteriaTransfer
     *
     * @return \Orm\Zed\PushNotification\Persistence\SpyPushNotificationGroupQuery
     */
    protected function applyPushNotificationGroupFilters(
        SpyPushNotificationGroupQuery $pushNotificationGroupQuery,
        PushNotificationGroupCriteriaTransfer $pushNotificationGroupCriteriaTransfer
    ): SpyPushNotificationGroupQuery {
        $pushNotificationGroupConditionsTransfer = $pushNotificationGroupCriteriaTransfer->getPushNotificationGroupConditions();

        if (!$pushNotificationGroupConditionsTransfer) {
            return $pushNotificationGroupQuery;
        }

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
     * @param \Generated\Shared\Transfer\PushNotificationProviderCriteriaTransfer $pushNotificationProviderCriteriaTransfer
     *
     * @return \Orm\Zed\PushNotification\Persistence\SpyPushNotificationProviderQuery
     */
    protected function applyPushNotificationProviderFilters(
        SpyPushNotificationProviderQuery $pushNotificationProviderQuery,
        PushNotificationProviderCriteriaTransfer $pushNotificationProviderCriteriaTransfer
    ): SpyPushNotificationProviderQuery {
        $pushNotificationProviderConditionsTransfer = $pushNotificationProviderCriteriaTransfer->getPushNotificationProviderConditions();

        if (!$pushNotificationProviderConditionsTransfer) {
            return $pushNotificationProviderQuery;
        }

        if ($pushNotificationProviderConditionsTransfer->getUuids()) {
            $pushNotificationProviderQuery->filterByUuid(
                $pushNotificationProviderConditionsTransfer->getUuids(),
                $pushNotificationProviderConditionsTransfer->getIsUuidsConditionInversed() ? Criteria::NOT_IN : Criteria::IN,
            );
        }

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

        if (!$pushNotificationConditionsTransfer) {
            return $pushNotificationQuery;
        }

        if ($pushNotificationConditionsTransfer->getPushNotificationIds()) {
            $pushNotificationQuery->filterByIdPushNotification(
                $pushNotificationConditionsTransfer->getPushNotificationIds(),
                Criteria::IN,
            );
        }

        if ($pushNotificationConditionsTransfer->getPushNotificationProviderIds()) {
            $pushNotificationQuery->filterByFkPushNotificationProvider_In(
                $pushNotificationConditionsTransfer->getPushNotificationProviderIds(),
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
                ->filterByIdPushNotificationSubscriptionDeliveryLog(null, Criteria::ISNOTNULL)
            ->endUse();

        return $pushNotificationQuery;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $modelCriteria
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function applyPagination(ModelCriteria $modelCriteria, PaginationTransfer $paginationTransfer): ModelCriteria
    {
        if ($paginationTransfer->getOffset() !== null && $paginationTransfer->getLimit() !== null) {
            $paginationTransfer->setNbResults($modelCriteria->count());

            return $modelCriteria
                ->offset($paginationTransfer->getOffsetOrFail())
                ->setLimit($paginationTransfer->getLimitOrFail());
        }

        if ($paginationTransfer->getPage() !== null && $paginationTransfer->getMaxPerPage()) {
            $propelModelPager = $modelCriteria->paginate(
                $paginationTransfer->getPageOrFail(),
                $paginationTransfer->getMaxPerPageOrFail(),
            );

            $this->getFactory()
                ->createPaginationMapper()
                ->mapPropelModelPagerToPaginationTransfer($propelModelPager, $paginationTransfer);

            return $propelModelPager->getQuery();
        }

        return $modelCriteria;
    }

    /**
     * @param \Orm\Zed\PushNotification\Persistence\SpyPushNotificationSubscriptionQuery $pushNotificationSubscriptionQuery
     * @param \Generated\Shared\Transfer\PushNotificationSubscriptionCriteriaTransfer $pushNotificationSubscriptionCriteriaTransfer
     *
     * @return \Orm\Zed\PushNotification\Persistence\SpyPushNotificationSubscriptionQuery
     */
    protected function applyPushNotificationSubscriptionFilters(
        SpyPushNotificationSubscriptionQuery $pushNotificationSubscriptionQuery,
        PushNotificationSubscriptionCriteriaTransfer $pushNotificationSubscriptionCriteriaTransfer
    ): SpyPushNotificationSubscriptionQuery {
        $pushNotificationSubscriptionConditionsTransfer = $pushNotificationSubscriptionCriteriaTransfer->getPushNotificationSubscriptionConditions();

        if (!$pushNotificationSubscriptionConditionsTransfer) {
            return $pushNotificationSubscriptionQuery;
        }

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
