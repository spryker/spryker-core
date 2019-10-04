<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileStorage\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\MerchantProfileStorageTransfer;
use Orm\Zed\MerchantProfileStorage\Persistence\SpyMerchantProfileStorage;

interface MerchantProfileStorageMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantProfileStorageTransfer $merchantProfileStorageTransfer
     * @param \Orm\Zed\MerchantProfileStorage\Persistence\SpyMerchantProfileStorage $spyMerchantProfileStorage
     *
     * @return \Orm\Zed\MerchantProfileStorage\Persistence\SpyMerchantProfileStorage
     */
    public function mapMerchantProfileStorageTransferToMerchantProfileStorageEntity(
        MerchantProfileStorageTransfer $merchantProfileStorageTransfer,
        SpyMerchantProfileStorage $spyMerchantProfileStorage
    ): SpyMerchantProfileStorage;

    /**
     * @param \Orm\Zed\MerchantProfileStorage\Persistence\SpyMerchantProfileStorage $spyMerchantProfileStorage
     * @param \Generated\Shared\Transfer\MerchantProfileStorageTransfer $merchantProfileStorageTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileStorageTransfer
     */
    public function mapMerchantProfileStorageEntityToMerchantProfileStorageTransfer(
        SpyMerchantProfileStorage $spyMerchantProfileStorage,
        MerchantProfileStorageTransfer $merchantProfileStorageTransfer
    ): MerchantProfileStorageTransfer;
}
