<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleStorage;

use Spryker\Shared\ConfigurableBundleStorage\ConfigurableBundleStorageConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class ConfigurableBundleStorageConfig extends AbstractBundleConfig
{
    /**
     * @return bool
     */
    public function isSendingToQueue(): bool
    {
        return $this->get(ConfigurableBundleStorageConstants::STORAGE_SYNC_ENABLED, true);
    }

    /**
     * @return string|null
     */
    public function getConfigurableBundleTemplateSynchronizationPoolName(): ?string
    {
        return 'synchronizationPool';
    }
}
