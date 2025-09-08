<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Lock;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class LockConfig extends AbstractBundleConfig
{
    /**
     * @var int Value in seconds
     */
    public const DEFAULT_LOCK_TIMEOUT = 30;

    /**
     * Specification:
     * - Returns the default TTL (time-to-live) for locks for Storage (Redis, etc.)
     *
     * @api
     *
     * @return float
     */
    public function getStorageInitialTtl(): float
    {
        return 300.0;
    }
}
