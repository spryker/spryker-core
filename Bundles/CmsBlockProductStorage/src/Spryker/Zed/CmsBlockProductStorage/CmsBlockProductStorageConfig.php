<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockProductStorage;

use Spryker\Shared\CmsBlockProductStorage\CmsBlockProductStorageConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class CmsBlockProductStorageConfig extends AbstractBundleConfig
{
    /**
     * @return bool
     */
    public function isSendingToQueue(): bool
    {
        return $this->get(CmsBlockProductStorageConstants::STORAGE_SYNC_ENABLED, true);
    }

    /**
     * @return string|null
     */
    public function getCmsBlockProductSynchronizationPoolName(): ?string
    {
        return null;
    }
}
