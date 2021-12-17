<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Kernel;

use Spryker\Shared\Kernel\AbstractLocatorLocator;
use Spryker\Shared\Kernel\BundleProxy;

class Locator extends AbstractLocatorLocator
{
    /**
     * @var \Spryker\Service\Kernel\Locator
     */
    private static $instance;

    /**
     * @internal
     *
     * @return self
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
        $bundleProxy = new BundleProxy();
        $bundleProxy
            ->addLocator(new ServiceLocator());

        return $bundleProxy;
    }
}
