<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantStorage\Mapper;

use Generated\Shared\Transfer\MerchantStorageTransfer;

class MerchantStorageMapper implements MerchantStorageMapperInterface
{
    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\MerchantStorageTransfer
     */
    public function mapMerchantStorageDataToMerchantStorageTransfer(array $data): MerchantStorageTransfer
    {
        $merchantStorageTransfer = new MerchantStorageTransfer();
        $merchantStorageTransfer->fromArray($data, true);

        return $merchantStorageTransfer;
    }
}
