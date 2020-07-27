<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUser\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\MerchantUserTransfer;
use Orm\Zed\Merchant\Persistence\SpyMerchant;
use Orm\Zed\MerchantUser\Persistence\SpyMerchantUser;
use Propel\Runtime\Collection\Collection;

class MerchantUserMapper
{
    /**
     * @param \Orm\Zed\MerchantUser\Persistence\SpyMerchantUser $merchantUserEntity
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantUserTransfer
     */
    public function mapMerchantUserEntityToMerchantUserTransfer(
        SpyMerchantUser $merchantUserEntity,
        MerchantUserTransfer $merchantUserTransfer
    ): MerchantUserTransfer {
        return $merchantUserTransfer->fromArray($merchantUserEntity->toArray(), true)
            ->setIdMerchant($merchantUserEntity->getFkMerchant())
            ->setIdUser($merchantUserEntity->getFkUser())
            ->setMerchant(
                $this->mapMerchantEntityToMerchantTransfer($merchantUserEntity->getSpyMerchant(), new MerchantTransfer())
            );
    }

    /**
     * @param \Orm\Zed\Merchant\Persistence\SpyMerchant $merchantUserEntity
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    protected function mapMerchantEntityToMerchantTransfer(
        SpyMerchant $merchantUserEntity,
        MerchantTransfer $merchantTransfer
    ): MerchantTransfer {
        return $merchantTransfer->fromArray($merchantUserEntity->toArray(), true);
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

        return $merchantUserEntity->setFkUser($merchantUserTransfer->getIdUser())
            ->setFkMerchant($merchantUserTransfer->getIdMerchant());
    }

    /**
     * @param \Propel\Runtime\Collection\Collection $merchantUserEntities
     *
     * @return \Generated\Shared\Transfer\MerchantUserTransfer[]
     */
    public function mapMerchantUserEntitiesToMerchantUserTransfers(Collection $merchantUserEntities): array
    {
        $merchantUserTransfers = [];

        foreach ($merchantUserEntities as $merchantUserEntity) {
            $merchantUserTransfers[] = $this->mapMerchantUserEntityToMerchantUserTransfer(
                $merchantUserEntity,
                new MerchantUserTransfer()
            );
        }

        return $merchantUserTransfers;
    }
}
