<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxStorage;

use Spryker\Shared\TaxStorage\TaxStorageConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class TaxStorageConfig extends AbstractBundleConfig
{
    /**
     * @return bool
     */
    public function isSendingToQueue(): bool
    {
        return $this->get(TaxStorageConstants::STORAGE_SYNC_ENABLED, true);
    }

    /**
     * @return string|null
     */
    public function getTaxSynchronizationPoolName(): ?string
    {
        return null;
    }
}
