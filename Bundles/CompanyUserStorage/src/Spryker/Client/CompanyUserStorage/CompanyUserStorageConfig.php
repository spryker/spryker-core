<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CompanyUserStorage;

use Spryker\Client\Kernel\AbstractBundleConfig;
use Spryker\Shared\CompanyUserStorage\CompanyUserStorageConstants;

class CompanyUserStorageConfig extends AbstractBundleConfig
{
    /**
     * @return bool
     */
    public function isSendingToQueue(): bool
    {
        return $this->get(CompanyUserStorageConstants::STORAGE_SYNC_ENABLED, true);
    }
}
