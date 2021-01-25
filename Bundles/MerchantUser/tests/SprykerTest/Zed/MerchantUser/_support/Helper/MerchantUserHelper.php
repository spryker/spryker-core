<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantUser\Helper;

use Codeception\Module;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\MerchantUserTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Orm\Zed\MerchantUser\Persistence\SpyMerchantUser;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;

class MerchantUserHelper extends Module
{
    use DataCleanupHelperTrait;

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantUserTransfer
     */
    public function haveMerchantUser(MerchantTransfer $merchantTransfer, UserTransfer $userTransfer): MerchantUserTransfer
    {
        $merchantUserEntity = $this->createMerchantUserEntity()
            ->setFkMerchant($merchantTransfer->getIdMerchant())
            ->setFkUser($userTransfer->getIdUser());

        $merchantUserEntity->save();

        $this->getDataCleanupHelper()->_addCleanup(function () use ($merchantUserEntity): void {
            $merchantUserEntity->delete();
        });

        return (new MerchantUserTransfer())->setIdMerchantUser($merchantUserEntity->getIdMerchantUser())
            ->setIdMerchant($merchantUserEntity->getFkMerchant())
            ->setIdUser($merchantUserEntity->getFkUser())
            ->setMerchant($merchantTransfer);
    }

    /**
     * @return \Orm\Zed\MerchantUser\Persistence\SpyMerchantUser
     */
    private function createMerchantUserEntity(): SpyMerchantUser
    {
        return new SpyMerchantUser();
    }
}
