<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\User\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\UserCollectionTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Orm\Zed\User\Persistence\SpyUser;
use Propel\Runtime\Collection\Collection;

class UserMapper
{
    /**
     * @param \Propel\Runtime\Collection\Collection<array-key, \Orm\Zed\User\Persistence\SpyUser> $userEntityCollection
     * @param \Generated\Shared\Transfer\UserCollectionTransfer $userCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\UserCollectionTransfer
     */
    public function mapUserEntityCollectionToUserCollectionTransfer(
        Collection $userEntityCollection,
        UserCollectionTransfer $userCollectionTransfer
    ): UserCollectionTransfer {
        foreach ($userEntityCollection as $userEntity) {
            $userCollectionTransfer->addUser($this->mapUserEntityToUserTransfer($userEntity, new UserTransfer()));
        }

        return $userCollectionTransfer;
    }

    /**
     * @param \Orm\Zed\User\Persistence\SpyUser $userEntity
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    protected function mapUserEntityToUserTransfer(SpyUser $userEntity, UserTransfer $userTransfer): UserTransfer
    {
        return $userTransfer->fromArray($userEntity->toArray(), true);
    }
}
