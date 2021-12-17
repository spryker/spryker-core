<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Kernel\Fixtures;

use Spryker\Shared\Kernel\AbstractLocatorLocator;
use Spryker\Shared\Kernel\BundleProxy;

class LocatorLocator extends AbstractLocatorLocator
{
    /**
     * @var \SprykerTest\Shared\Kernel\Fixtures\LocatorLocator
     */
    private static $instance;

    /**
     * @internal
     *
     * @return static
     */
    public static function getInstance(): self
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * @return \Spryker\Shared\Kernel\BundleProxy
     */
    protected function getBundleProxy(): BundleProxy
    {
        return new BundleProxy();
    }
}
