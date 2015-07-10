<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Shared\Kernel;

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
     * @var AbstractLocatorLocator
     */
    private static $instance;

    /**
     * @param array $locator
     *
     * @return AbstractLocatorLocator|static
     */
    public static function getInstance(array $locator = null)
    {
        if (is_null(self::$instance)) {
            self::$instance = new static();
        }

        self::$instance->setLocator($locator);

        return self::$instance;
    }

    /**
     * @param array $locator
     */
    private function setLocator(array $locator = null)
    {
        $this->locator = $locator;
    }

    final private function __construct()
    {
    }

    final private function __clone()
    {
    }

    /**
     * @param string $bundle
     * @param array  $arguments
     *
     * @return BundleProxy
     */
    public function __call($bundle, array $arguments = null)
    {
        if (is_null($this->bundleProxy)) {
            $this->bundleProxy = $this->getBundleProxy();
        }

        return $this->bundleProxy->setBundle($bundle);
    }

    /**
     * @return BundleProxy
     */
    abstract protected function getBundleProxy();

}
