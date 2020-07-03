<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantProductStorage\Mapper;

use Generated\Shared\Transfer\MerchantProductStorageTransfer;

class MerchantProductStorageMapper implements MerchantProductStorageMapperInterface
{
    /**
     * @param array<mixed> $productStorageData
     * @param \Generated\Shared\Transfer\MerchantProductStorageTransfer $merchantProductStorageTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProductStorageTransfer
     */
    public function mapProductStorageDataToMerchantProductStorageTransfer(
        array $productStorageData,
        MerchantProductStorageTransfer $merchantProductStorageTransfer
    ): MerchantProductStorageTransfer {
        return $merchantProductStorageTransfer->fromArray($productStorageData, true);
    }
}
