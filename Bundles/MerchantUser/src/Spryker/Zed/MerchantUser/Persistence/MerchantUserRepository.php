<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUser\Persistence;

use Generated\Shared\Transfer\MerchantUserCriteriaTransfer;
use Generated\Shared\Transfer\MerchantUserTransfer;
use Orm\Zed\MerchantUser\Persistence\SpyMerchantUserQuery;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\MerchantUser\Persistence\MerchantUserPersistenceFactory getFactory()
 */
class MerchantUserRepository extends AbstractRepository implements MerchantUserRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantUserCriteriaTransfer $merchantUserCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantUserTransfer|null
     */
    public function findOne(MerchantUserCriteriaTransfer $merchantUserCriteriaTransfer): ?MerchantUserTransfer
    {
        $merchantUserQuery = $this->getFactory()->createMerchantUserPropelQuery();
        $merchantUserQuery = $this->applyCriteria($merchantUserQuery, $merchantUserCriteriaTransfer);

        $merchantUserEntity = $merchantUserQuery->findOne();

        if (!$merchantUserEntity) {
            return null;
        }

        return $this->getFactory()
            ->createMerchantUserMapper()
            ->mapMerchantUserEntityToMerchantUserTransfer($merchantUserEntity, new MerchantUserTransfer());
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantUserCriteriaTransfer $merchantUserCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantUserTransfer[]
     */
    public function find(MerchantUserCriteriaTransfer $merchantUserCriteriaTransfer): array
    {
        $merchantUserTransfers = [];
        $merchantUsersQuery = $this->getFactory()->createMerchantUserPropelQuery();
        $merchantUsersQuery = $this->applyCriteria($merchantUsersQuery, $merchantUserCriteriaTransfer);

        $merchantUserEntities = $merchantUsersQuery->find();

        foreach ($merchantUserEntities as $merchantUserEntity) {
            $merchantUserTransfers[] = $this->getFactory()->createMerchantUserMapper()
                ->mapMerchantUserEntityToMerchantUserTransfer($merchantUserEntity, new MerchantUserTransfer());
        }

        return $merchantUserTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantUserCriteriaTransfer $merchantUserCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantUserTransfer[]
     */
    public function getMerchantUsers(MerchantUserCriteriaTransfer $merchantUserCriteriaTransfer): array
    {
        $merchantUserQuery = $this->getFactory()->createMerchantUserPropelQuery();
        $merchantUserQuery->joinWithSpyMerchant();
        $merchantUserQuery = $this->applyCriteria($merchantUserQuery, $merchantUserCriteriaTransfer);

        $merchantUserEntities = $merchantUserQuery->find();

        return $this->getFactory()
            ->createMerchantUserMapper()
            ->mapMerchantUserEntitiesToMerchantUserTransfers($merchantUserEntities);
    }

    /**
     * @param \Orm\Zed\MerchantUser\Persistence\SpyMerchantUserQuery $merchantUserQuery
     * @param \Generated\Shared\Transfer\MerchantUserCriteriaTransfer $merchantUserCriteriaTransfer
     *
     * @return \Orm\Zed\MerchantUser\Persistence\SpyMerchantUserQuery
     */
    protected function applyCriteria(
        SpyMerchantUserQuery $merchantUserQuery,
        MerchantUserCriteriaTransfer $merchantUserCriteriaTransfer
    ): SpyMerchantUserQuery {
        if ($merchantUserCriteriaTransfer->getIdUser() !== null) {
            $merchantUserQuery->filterByFkUser($merchantUserCriteriaTransfer->getIdUser());
        }

        if ($merchantUserCriteriaTransfer->getIdMerchant() !== null) {
            $merchantUserQuery->filterByFkMerchant($merchantUserCriteriaTransfer->getIdMerchant());
        }

        if ($merchantUserCriteriaTransfer->getIdMerchantUser() !== null) {
            $merchantUserQuery->filterByIdMerchantUser($merchantUserCriteriaTransfer->getIdMerchantUser());
        }

        $merchantUserQuery->orderByIdMerchantUser();

        return $merchantUserQuery;
    }
}
