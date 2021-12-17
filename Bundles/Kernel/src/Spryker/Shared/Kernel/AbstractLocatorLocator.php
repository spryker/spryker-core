<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel;

abstract class AbstractLocatorLocator implements LocatorLocatorInterface
{
    /**
     * @var \Spryker\Shared\Kernel\BundleProxy
     */
    protected $bundleProxy;

    /**
     * @var array<\Spryker\Shared\Kernel\AbstractLocator>
     */
    protected $locator;

    /**
     * @var static
     */
    private static $instance;

    /**
     * @internal
     *
     * @return static
     */
    public static function getInstance()
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * Should be private, because this class uses `Singleton` pattern.
     *
     * @internal
     */
    protected function __construct()
    {
    }

    /**
     * Should be private, because this class uses `Singleton` pattern.
     *
     * @internal
     *
     * @return void
     */
    protected function __clone()
    {
    }

    /**
     * @param string $bundle
     * @param array|null $arguments
     *
     * @return \Spryker\Shared\Kernel\BundleProxy
     */
    public function __call($bundle, ?array $arguments = null)
    {
        if ($this->bundleProxy === null) {
            $this->bundleProxy = $this->getBundleProxy();
        }

        return $this->bundleProxy->setBundle($bundle);
    }

    /**
     * @return \Spryker\Shared\Kernel\BundleProxy
     */
    abstract protected function getBundleProxy();
}
