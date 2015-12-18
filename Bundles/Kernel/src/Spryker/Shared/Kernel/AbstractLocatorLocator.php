<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Shared\Kernel;

/**
 * Class AbstractLocatorLocator
 */

abstract class AbstractLocatorLocator implements LocatorLocatorInterface
{

    /**
     * @var BundleProxy
     */
    private $bundleProxy;

    /**
     * @var array
     */
    protected $locator;

    /**
     * @var self
     */
    private static $instance;

    /**
     * @return AbstractLocatorLocator|static
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    final private function __construct()
    {
    }

    /**
     * @return void
     */
    final private function __clone()
    {
    }

    /**
     * @param string $bundle
     * @param array $arguments
     *
     * @return BundleProxy
     */
    public function __call($bundle, array $arguments = null)
    {
        if ($this->bundleProxy === null) {
            $this->bundleProxy = $this->getBundleProxy();
        }

        return $this->bundleProxy->setBundle($bundle);
    }

    /**
     * @return BundleProxy
     */
    abstract protected function getBundleProxy();

}
