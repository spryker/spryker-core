<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImageStorage;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class CategoryImageStorageConfig extends AbstractBundleConfig
{
    /**
     * @return string|null
     */
    public function getProductImageSynchronizationPoolName(): ?string
    {
        return null;
    }
}
