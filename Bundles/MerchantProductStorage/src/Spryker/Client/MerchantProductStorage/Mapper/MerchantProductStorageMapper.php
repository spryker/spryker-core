<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantProductStorage\Mapper;

use Generated\Shared\Transfer\MerchantProductStorageTransfer;

class MerchantProductStorageMapper
{
    /**
     * @param array $merchantProductStorageData
     * @param \Generated\Shared\Transfer\MerchantProductStorageTransfer $merchantProductStorageTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProductStorageTransfer
     */
    public function mapMerchantProductStorageDataToMerchantProductStorageTransfer(
        array $merchantProductStorageData,
        MerchantProductStorageTransfer $merchantProductStorageTransfer
    ): MerchantProductStorageTransfer {
        return $merchantProductStorageTransfer->fromArray($merchantProductStorageData, true);
    }
}
