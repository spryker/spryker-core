<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantStorage\Persistence\Mapper;

use Generated\Shared\Transfer\MerchantStorageTransfer;
use Orm\Zed\MerchantStorage\Persistence\SpyMerchantStorage;

class MerchantStorageMapper
{
    /**
     * @param \Generated\Shared\Transfer\MerchantStorageTransfer $merchantStorageTransfer
     * @param \Orm\Zed\MerchantStorage\Persistence\SpyMerchantStorage $merchantStorageEntity
     *
     * @return \Orm\Zed\MerchantStorage\Persistence\SpyMerchantStorage
     */
    public function mapMerchantStorageTransferToMerchantStorageEntity(
        MerchantStorageTransfer $merchantStorageTransfer,
        SpyMerchantStorage $merchantStorageEntity
    ) {
        $merchantStorageEntity->fromArray($merchantStorageTransfer->modifiedToArray(false));

        return $merchantStorageEntity;
    }

    /**
     * @param \Orm\Zed\MerchantStorage\Persistence\SpyMerchantStorage $merchantStorageEntity
     * @param \Generated\Shared\Transfer\MerchantStorageTransfer $merchantStorageTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantStorageTransfer
     */
    public function mapMerchantStorageEntityToMerchantStorageTransfer(
        SpyMerchantStorage $merchantStorageEntity,
        MerchantStorageTransfer $merchantStorageTransfer
    ) {
        return $merchantStorageTransfer->fromArray($merchantStorageEntity->toArray(), true);
    }
}
