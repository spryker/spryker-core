<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\NavigationStorage;

use Spryker\Client\Kernel\AbstractBundleConfig;

class NavigationStorageConfig extends AbstractBundleConfig
{
    /**
     * To be able to work with data exported with collectors to redis, we need to bring this module into compatibility
     * mode. If this is turned on the NavigationClient will be used instead.
     *
     * @return bool
     */
    public static function isCollectorCompatibilityMode(): bool
    {
        return false;
    }
}
