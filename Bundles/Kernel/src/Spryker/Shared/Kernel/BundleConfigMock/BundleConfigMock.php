<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\BundleConfigMock;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class BundleConfigMock
{
    /**
     * @var \Spryker\Shared\Kernel\AbstractBundleConfig[]
     */
    protected static $bundleConfigMocks = [];

    /**
     * @param \Spryker\Shared\Kernel\AbstractBundleConfig $bundleConfig
     *
     * @return void
     */
    public function addBundleConfigMock(AbstractBundleConfig $bundleConfig)
    {
        static::$bundleConfigMocks[get_class(($bundleConfig))] = $bundleConfig;
    }

    /**
     * @param \Spryker\Shared\Kernel\AbstractBundleConfig $bundleConfig
     *
     * @return bool
     */
    public function hasBundleConfigMock(AbstractBundleConfig $bundleConfig)
    {
        if (isset(static::$bundleConfigMocks[get_class(($bundleConfig))])) {
            return true;
        }

        return false;
    }

    /**
     * @param \Spryker\Shared\Kernel\AbstractBundleConfig $bundleConfig
     *
     * @return \Spryker\Shared\Kernel\AbstractBundleConfig
     */
    public function getBundleConfigMock(AbstractBundleConfig $bundleConfig)
    {
        return static::$bundleConfigMocks[get_class(($bundleConfig))];
    }

    /**
     * @return void
     */
    public function reset()
    {
        static::$bundleConfigMocks = [];
    }
}
