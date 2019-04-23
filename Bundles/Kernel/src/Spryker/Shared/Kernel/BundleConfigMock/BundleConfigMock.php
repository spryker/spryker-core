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
     * @param string|null $className
     */
    public function addBundleConfigMock(AbstractBundleConfig $bundleConfig, string $className = null)
    {
        static::$bundleConfigMocks[$this->getBundleConfigMockKey($bundleConfig, $className)] = $bundleConfig;
    }

    /**
     * @param \Spryker\Shared\Kernel\AbstractBundleConfig $bundleConfig
     * @param string|null $className
     *
     * @return bool
     */
    public function hasBundleConfigMock(AbstractBundleConfig $bundleConfig, string $className = null)
    {
        if (isset(static::$bundleConfigMocks[$this->getBundleConfigMockKey($bundleConfig, $className)])) {
            return true;
        }

        return false;
    }

    /**
     * @param \Spryker\Shared\Kernel\AbstractBundleConfig $bundleConfig
     * @param string|null $className
     *
     * @return \Spryker\Shared\Kernel\AbstractBundleConfig
     */
    public function getBundleConfigMock(AbstractBundleConfig $bundleConfig, string $className = null)
    {
        return static::$bundleConfigMocks[$this->getBundleConfigMockKey($bundleConfig, $className)];
    }

    /**
     * @return void
     */
    public function reset()
    {
        static::$bundleConfigMocks = [];
    }

    /**
     * @param \Spryker\Shared\Kernel\AbstractBundleConfig $bundleConfig
     * @param string|null $className
     *
     * @return string
     */
    protected function getBundleConfigMockKey(AbstractBundleConfig $bundleConfig, string $className = null): string
    {
        return ($className ?: get_class($bundleConfig));
    }
}
