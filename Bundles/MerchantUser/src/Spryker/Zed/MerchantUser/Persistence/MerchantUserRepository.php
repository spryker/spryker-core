<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUser\Persistence;

use Generated\Shared\Transfer\MerchantUserCriteriaFilterTransfer;
use Generated\Shared\Transfer\MerchantUserTransfer;
use Orm\Zed\MerchantUser\Persistence\SpyMerchantUserQuery;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\MerchantUser\Persistence\MerchantUserPersistenceFactory getFactory()
 */
class MerchantUserRepository extends AbstractRepository implements MerchantUserRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantUserCriteriaFilterTransfer $merchantUserCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantUserTransfer|null
     */
    public function findOne(MerchantUserCriteriaFilterTransfer $merchantUserCriteriaFilterTransfer): ?MerchantUserTransfer
    {
        $merchantUserQuery = $this->getFactory()->createMerchantUserPropelQuery();
        $merchantUserQuery = $this->applyCriteria($merchantUserQuery, $merchantUserCriteriaFilterTransfer);

        $merchantUserEntity = $merchantUserQuery->findOne();

        if (!$merchantUserEntity) {
            return null;
        }

        return $this->getFactory()
            ->createMerchantUserMapper()
            ->mapMerchantUserEntityToMerchantUserTransfer($merchantUserEntity, new MerchantUserTransfer());
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantUserCriteriaFilterTransfer $merchantUserCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantUserTransfer[]
     */
    public function getMerchantUsers(MerchantUserCriteriaFilterTransfer $merchantUserCriteriaFilterTransfer): array
    {
        $merchantUserQuery = $this->getFactory()->createMerchantUserPropelQuery();
        $merchantUserQuery = $this->applyCriteria($merchantUserQuery, $merchantUserCriteriaFilterTransfer);

        $merchantUserEntities = $merchantUserQuery->find();

        return $this->getFactory()
            ->createMerchantUserMapper()
            ->mapMerchantUserEntitiesToMerchantUserTransfers($merchantUserEntities);
    }

    /**
     * @param \Orm\Zed\MerchantUser\Persistence\SpyMerchantUserQuery $merchantUserQuery
     * @param \Generated\Shared\Transfer\MerchantUserCriteriaFilterTransfer $merchantUserCriteriaFilterTransfer
     *
     * @return \Orm\Zed\MerchantUser\Persistence\SpyMerchantUserQuery
     */
    protected function applyCriteria(
        SpyMerchantUserQuery $merchantUserQuery,
        MerchantUserCriteriaFilterTransfer $merchantUserCriteriaFilterTransfer
    ): SpyMerchantUserQuery {
        if ($merchantUserCriteriaFilterTransfer->getIdUser()) {
            $merchantUserQuery->filterByFkUser($merchantUserCriteriaFilterTransfer->getIdUser());
        }

        if ($merchantUserCriteriaFilterTransfer->getIdMerchant()) {
            $merchantUserQuery->filterByFkMerchant($merchantUserCriteriaFilterTransfer->getIdMerchant());
        }

        $merchantUserQuery->orderByIdMerchantUser();

        return $merchantUserQuery;
    }
}
