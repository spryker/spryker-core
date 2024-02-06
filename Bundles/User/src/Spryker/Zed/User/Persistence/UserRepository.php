<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\User\Persistence;

use Generated\Shared\Transfer\QueryCriteriaTransfer;
use Generated\Shared\Transfer\UserCollectionTransfer;
use Generated\Shared\Transfer\UserConditionsTransfer;
use Generated\Shared\Transfer\UserCriteriaTransfer;
use Orm\Zed\User\Persistence\SpyUserQuery;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\User\Persistence\UserPersistenceFactory getFactory()
 */
class UserRepository extends AbstractRepository implements UserRepositoryInterface
{
    /**
     * @uses \Orm\Zed\User\Persistence\Map\SpyUserTableMap::COL_UUID
     *
     * @var string
     */
    protected const COLUMN_UUID = 'uuid';

    /**
     * @param \Generated\Shared\Transfer\UserCriteriaTransfer $userCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\UserCollectionTransfer
     */
    public function getUserCollection(UserCriteriaTransfer $userCriteriaTransfer): UserCollectionTransfer
    {
        $userQuery = $this->getFactory()->createUserQuery();
        if ($userCriteriaTransfer->getUserConditions()) {
            $userQuery = $this->applyUserFilters($userQuery, $userCriteriaTransfer->getUserConditionsOrFail());
        }

        $userQuery = $this->expandUserQuery($userQuery, $userCriteriaTransfer);

        $userEntityCollection = $userQuery->find();

        $userCollectionTransfer = new UserCollectionTransfer();
        if ($userEntityCollection->count() === 0) {
            return $userCollectionTransfer;
        }

        return $this->getFactory()
            ->createUserMapper()
            ->mapUserEntityCollectionToUserCollectionTransfer(
                $userEntityCollection,
                $userCollectionTransfer,
            );
    }

    /**
     * @param \Orm\Zed\User\Persistence\SpyUserQuery $userQuery
     * @param \Generated\Shared\Transfer\UserConditionsTransfer $userConditionsTransfer
     *
     * @return \Orm\Zed\User\Persistence\SpyUserQuery
     */
    protected function applyUserFilters(SpyUserQuery $userQuery, UserConditionsTransfer $userConditionsTransfer): SpyUserQuery
    {
        if ($userConditionsTransfer->getUserIds() !== []) {
            $userQuery->filterByIdUser_In($userConditionsTransfer->getUserIds());
        }

        if ($userConditionsTransfer->getUsernames() !== []) {
            $userQuery->filterByUsername_In($userConditionsTransfer->getUsernames());
        }

        if ($userConditionsTransfer->getStatuses() !== []) {
            $userQuery->filterByStatus_In($userConditionsTransfer->getStatuses());
        }

        if ($userConditionsTransfer->getUuids() !== [] && $userQuery->getTableMapOrFail()->hasColumn(static::COLUMN_UUID)) {
            $userQuery->filterByUuid_In($userConditionsTransfer->getUuids());
        }

        return $userQuery;
    }

    /**
     * @param \Orm\Zed\User\Persistence\SpyUserQuery $userQuery
     * @param \Generated\Shared\Transfer\UserCriteriaTransfer $userCriteriaTransfer
     *
     * @return \Orm\Zed\User\Persistence\SpyUserQuery
     */
    protected function expandUserQuery(
        SpyUserQuery $userQuery,
        UserCriteriaTransfer $userCriteriaTransfer
    ): SpyUserQuery {
        $queryCriteriaTransfer = $this->executeUserQueryCriteriaExpanderPlugins($userCriteriaTransfer);

        return $this->getFactory()
            ->createUserQueryCriteriaMapper()
            ->mapQueryCriteriaTransferToUserQueryCriteria(
                $queryCriteriaTransfer,
                $userQuery,
            );
    }

    /**
     * @param \Generated\Shared\Transfer\UserCriteriaTransfer $userCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\QueryCriteriaTransfer
     */
    protected function executeUserQueryCriteriaExpanderPlugins(
        UserCriteriaTransfer $userCriteriaTransfer
    ): QueryCriteriaTransfer {
        $queryCriteriaTransfer = new QueryCriteriaTransfer();
        foreach ($this->getFactory()->getUserQueryCriteriaExpanderPlugins() as $userQueryCriteriaExpanderPlugin) {
            $queryCriteriaTransfer = $userQueryCriteriaExpanderPlugin->expand($queryCriteriaTransfer, $userCriteriaTransfer);
        }

        return $queryCriteriaTransfer;
    }
}
