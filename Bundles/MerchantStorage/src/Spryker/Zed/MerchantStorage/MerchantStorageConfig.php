<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantStorage;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class MerchantStorageConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @return string|null
     */
    public function getMerchantSynchronizationPoolName(): ?string
    {
        return null;
    }

    /**
     * @api
     *
     * @return bool
     */
    public function isSendingToQueue(): bool
    {
        return true;
    }
}
