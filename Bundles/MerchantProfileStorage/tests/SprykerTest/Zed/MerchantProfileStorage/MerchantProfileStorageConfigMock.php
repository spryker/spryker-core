<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantProfileStorage;

use Spryker\Zed\MerchantProfileStorage\MerchantProfileStorageConfig;

class MerchantProfileStorageConfigMock extends MerchantProfileStorageConfig
{
    /**
     * @return bool
     */
    public function isSendingToQueue(): bool
    {
        return false;
    }
}
