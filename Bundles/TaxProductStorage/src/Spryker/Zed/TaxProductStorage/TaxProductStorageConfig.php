<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxProductStorage;

use Spryker\Shared\TaxProductStorage\TaxProductStorageConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class TaxProductStorageConfig extends AbstractBundleConfig
{
    /**
     * @return bool
     */
    public function isSendingToQueue(): bool
    {
        return $this->get(TaxProductStorageConstants::STORAGE_SYNC_ENABLED, true);
    }

    /**
     * @return string|null
     */
    public function getTaxProductSynchronizationPoolName(): ?string
    {
        return null;
    }
}
