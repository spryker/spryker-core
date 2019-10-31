<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileStorage\Persistence;

use Generated\Shared\Transfer\MerchantProfileTransfer;

interface MerchantProfileStorageEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantProfileTransfer $merchantProfileTransfer
     *
     * @return void
     */
    public function saveMerchantProfileStorage(MerchantProfileTransfer $merchantProfileTransfer): void;

    /**
     * @param int[] $merchantIds
     *
     * @return void
     */
    public function deleteMerchantProfileStorageEntitiesByMerchantIds(array $merchantIds): void;
}
