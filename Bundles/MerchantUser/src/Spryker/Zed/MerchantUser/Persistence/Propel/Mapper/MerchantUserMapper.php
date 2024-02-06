<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUser\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\MerchantUserCollectionTransfer;
use Generated\Shared\Transfer\MerchantUserTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Orm\Zed\Merchant\Persistence\SpyMerchant;
use Orm\Zed\MerchantUser\Persistence\SpyMerchantUser;
use Orm\Zed\User\Persistence\SpyUser;
use Propel\Runtime\Collection\Collection;

class MerchantUserMapper
{
    /**
     * @param \Orm\Zed\MerchantUser\Persistence\SpyMerchantUser $merchantUserEntity
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     * @param bool $withUser
     *
     * @return \Generated\Shared\Transfer\MerchantUserTransfer
     */
    public function mapMerchantUserEntityToMerchantUserTransfer(
        SpyMerchantUser $merchantUserEntity,
        MerchantUserTransfer $merchantUserTransfer,
        bool $withUser = false
    ): MerchantUserTransfer {
        $merchantUserTransfer = $merchantUserTransfer->fromArray($merchantUserEntity->toArray(), true)
            ->setIdMerchant($merchantUserEntity->getFkMerchant())
            ->setIdUser($merchantUserEntity->getFkUser())
            ->setMerchant(
                $this->mapMerchantEntityToMerchantTransfer($merchantUserEntity->getSpyMerchant(), new MerchantTransfer()),
            );

        if ($withUser) {
            $merchantUserTransfer->setUser(
                $this->mapUserEntityToUserTransfer($merchantUserEntity->getSpyUser(), new UserTransfer()),
            );
        }

        return $merchantUserTransfer;
    }

    /**
     * @param \Orm\Zed\Merchant\Persistence\SpyMerchant $merchantEntity
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    protected function mapMerchantEntityToMerchantTransfer(
        SpyMerchant $merchantEntity,
        MerchantTransfer $merchantTransfer
    ): MerchantTransfer {
        return $merchantTransfer->fromArray($merchantEntity->toArray(), true);
    }

    /**
     * @param \Orm\Zed\User\Persistence\SpyUser $userEntity
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    protected function mapUserEntityToUserTransfer(
        SpyUser $userEntity,
        UserTransfer $userTransfer
    ): UserTransfer {
        return $userTransfer->fromArray($userEntity->toArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     * @param \Orm\Zed\MerchantUser\Persistence\SpyMerchantUser $merchantUserEntity
     *
     * @return \Orm\Zed\MerchantUser\Persistence\SpyMerchantUser
     */
    public function mapMerchantUserTransferToMerchantUserEntity(
        MerchantUserTransfer $merchantUserTransfer,
        SpyMerchantUser $merchantUserEntity
    ): SpyMerchantUser {
        $merchantUserEntity->fromArray($merchantUserTransfer->toArray());

        return $merchantUserEntity->setFkUser($merchantUserTransfer->getIdUserOrFail())
            ->setFkMerchant($merchantUserTransfer->getIdMerchantOrFail());
    }

    /**
     * @param \Propel\Runtime\Collection\Collection<\Orm\Zed\MerchantUser\Persistence\SpyMerchantUser> $merchantUserEntities
     *
     * @return array<\Generated\Shared\Transfer\MerchantUserTransfer>
     */
    public function mapMerchantUserEntitiesToMerchantUserTransfers(Collection $merchantUserEntities): array
    {
        $merchantUserTransfers = [];

        foreach ($merchantUserEntities as $merchantUserEntity) {
            $merchantUserTransfers[] = $this->mapMerchantUserEntityToMerchantUserTransfer(
                $merchantUserEntity,
                new MerchantUserTransfer(),
            );
        }

        return $merchantUserTransfers;
    }

    /**
     * @param \Propel\Runtime\Collection\Collection<\Orm\Zed\MerchantUser\Persistence\SpyMerchantUser> $merchantUserEntities
     * @param \Generated\Shared\Transfer\MerchantUserCollectionTransfer $merchantUserCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantUserCollectionTransfer
     */
    public function mapMerchantUserEntitiesToMerchantUserCollectionTransfer(
        Collection $merchantUserEntities,
        MerchantUserCollectionTransfer $merchantUserCollectionTransfer
    ): MerchantUserCollectionTransfer {
        foreach ($merchantUserEntities as $merchantUserEntity) {
            $merchantUserCollectionTransfer->addMerchantUser(
                $this->mapMerchantUserEntityToMerchantUserTransfer($merchantUserEntity, new MerchantUserTransfer(), true),
            );
        }

        return $merchantUserCollectionTransfer;
    }
}
