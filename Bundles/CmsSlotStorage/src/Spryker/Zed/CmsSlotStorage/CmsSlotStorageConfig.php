<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotStorage;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class CmsSlotStorageConfig extends AbstractBundleConfig
{
    /**
     * @return string|null
     */
    public function getCmsSlotStorageSynchronizationPoolName(): ?string
    {
        return null;
    }
}
