<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockCategoryStorage;

use Spryker\Shared\CmsBlockCategoryStorage\CmsBlockCategoryStorageConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class CmsBlockCategoryStorageConfig extends AbstractBundleConfig
{
    /**
     * @return bool
     */
    public function isSendingToQueue(): bool
    {
        return $this->get(CmsBlockCategoryStorageConstants::STORAGE_SYNC_ENABLED, true);
    }

    /**
     * @return string|null
     */
    public function getCmsBlockCategorySynchronizationPoolName(): ?string
    {
        return null;
    }
}
