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
     * @var \Spryker\Shared\Kernel\AbstractLocator[]
     */
    protected $locator;

    /**
     * @var static
     */
    private static $instance;

    /**
     * @return static
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    /**
     * Should be private, because this class uses `Singleton` pattern.
     */
    private function __construct()
    {
    }

    /**
     * Should be private, because this class uses `Singleton` pattern.
     *
     * @return void
     */
    private function __clone()
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
