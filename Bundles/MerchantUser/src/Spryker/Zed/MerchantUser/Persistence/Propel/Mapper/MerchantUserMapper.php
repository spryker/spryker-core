<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUser\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\MerchantUserTransfer;
use Orm\Zed\MerchantUser\Persistence\SpyMerchantUser;

class MerchantUserMapper
{
    /**
     * @param \Orm\Zed\MerchantUser\Persistence\SpyMerchantUser $spyMerchantUser
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantUserTransfer
     */
    public function mapMerchantUserEntityToMerchantUserTransfer(
        SpyMerchantUser $spyMerchantUser,
        MerchantUserTransfer $merchantUserTransfer
    ): MerchantUserTransfer {
        $merchantUserTransfer = $merchantUserTransfer->fromArray($spyMerchantUser->toArray(), true);

        return $merchantUserTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     * @param \Orm\Zed\MerchantUser\Persistence\SpyMerchantUser $spyMerchantUser
     *
     * @return \Orm\Zed\MerchantUser\Persistence\SpyMerchantUser
     */
    public function mapMerchantUserTransferToMerchantUserEntity(
        MerchantUserTransfer $merchantUserTransfer,
        SpyMerchantUser $spyMerchantUser
    ): SpyMerchantUser {
        $spyMerchantUser->fromArray($merchantUserTransfer->toArray());

        return $spyMerchantUser;
    }
}
