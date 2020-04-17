<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUser\Persistence;

use Generated\Shared\Transfer\MerchantUserTransfer;
use Orm\Zed\MerchantUser\Persistence\SpyMerchantUser;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\MerchantUser\Persistence\MerchantUserPersistenceFactory getFactory()
 */
class MerchantUserEntityManager extends AbstractEntityManager implements MerchantUserEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantUserTransfer
     */
    public function create(MerchantUserTransfer $merchantUserTransfer): MerchantUserTransfer
    {
        $merchantUserMapper = $this->getFactory()->createMerchantUserMapper();
        $merchantUserEntity = $merchantUserMapper
            ->mapMerchantUserTransferToMerchantUserEntity($merchantUserTransfer, new SpyMerchantUser());

        $merchantUserEntity->save();

        return $merchantUserMapper->mapMerchantUserEntityToMerchantUserTransfer($merchantUserEntity, $merchantUserTransfer);
    }
}
