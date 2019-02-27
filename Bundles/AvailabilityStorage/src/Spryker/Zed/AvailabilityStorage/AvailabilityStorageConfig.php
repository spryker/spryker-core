<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityStorage;

use Spryker\Shared\AvailabilityStorage\AvailabilityStorageConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class AvailabilityStorageConfig extends AbstractBundleConfig
{
    /**
     * @return bool
     */
    public function isSendingToQueue(): bool
    {
        return $this->get(AvailabilityStorageConstants::STORAGE_SYNC_ENABLED, true);
    }

    /**
     * @return string|null
     */
    public function getSynchronizationPoolName(): ?string
    {
        return null;
    }
}
