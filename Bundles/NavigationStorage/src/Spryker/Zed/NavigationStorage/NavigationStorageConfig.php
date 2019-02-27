<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\NavigationStorage;

use Spryker\Shared\NavigationStorage\NavigationStorageConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class NavigationStorageConfig extends AbstractBundleConfig
{
    /**
     * @return bool
     */
    public function isSendingToQueue(): bool
    {
        return $this->get(NavigationStorageConstants::STORAGE_SYNC_ENABLED, true);
    }

    /**
     * @return string|null
     */
    public function getNavigationSynchronizationPoolName(): ?string
    {
        return null;
    }
}
