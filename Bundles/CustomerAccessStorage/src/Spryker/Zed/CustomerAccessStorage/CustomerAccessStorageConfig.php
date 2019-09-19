<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerAccessStorage;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class CustomerAccessStorageConfig extends AbstractBundleConfig
{
    /**
     * @deprecated Will be removed without replacement.
     *
     * @return bool
     */
    public function isSendingToQueue()
    {
        return true;
    }

    /**
     * @return string|null
     */
    public function getSynchronizationPoolName(): ?string
    {
        return null;
    }
}
