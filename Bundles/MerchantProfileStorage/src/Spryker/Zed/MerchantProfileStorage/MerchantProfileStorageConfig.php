<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileStorage;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class MerchantProfileStorageConfig extends AbstractBundleConfig
{
    /**
     * @return string|null
     */
    public function getMerchantProfileSynchronizationPoolName(): ?string
    {
        return null;
    }

    /**
     * @return bool
     */
    public function isSendingToQueue(): bool
    {
        return true;
    }
}
