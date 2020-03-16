<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantStorage\Persistence;

use Generated\Shared\Transfer\MerchantStorageTransfer;

interface MerchantStorageEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantStorageTransfer $merchantStorageTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantStorageTransfer
     */
    public function saveMerchantStorage(MerchantStorageTransfer $merchantStorageTransfer): MerchantStorageTransfer;
}
