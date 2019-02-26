<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GlossaryStorage;

use Spryker\Shared\GlossaryStorage\GlossaryStorageConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class GlossaryStorageConfig extends AbstractBundleConfig
{
    /**
     * @return bool
     */
    public function isSendingToQueue(): bool
    {
        return $this->get(GlossaryStorageConstants::STORAGE_SYNC_ENABLED, true);
    }

    /**
     * @return string|null
     */
    public function getGlossarySynchronizationPoolName(): ?string
    {
        return null;
    }
}
