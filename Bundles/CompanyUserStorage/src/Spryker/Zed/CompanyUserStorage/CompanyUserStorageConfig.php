<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserStorage;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class CompanyUserStorageConfig extends AbstractBundleConfig
{
    /**
     * @return string|null
     */
    public function getCompanyUserSynchronizationPoolName(): ?string
    {
        return null;
    }
}
