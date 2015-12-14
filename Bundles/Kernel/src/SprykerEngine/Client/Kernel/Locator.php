<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Client\Kernel;

use SprykerEngine\Shared\Kernel\BundleProxy;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;

class Locator implements LocatorLocatorInterface
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
     * @param array $locator
     *
     * @return self
     */
    public static function getInstance(array $locator = null)
    {
        if (self::$instance === null) {
            self::$instance = new static();
        }

        self::$instance->setLocator($locator);

        return self::$instance;
    }

    /**
     * @param array $locator
     *
     * @return void
     */
    private function setLocator(array $locator = null)
    {
        $this->locator = $locator;
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
    protected function getBundleProxy()
    {
        $bundleProxy = new BundleProxy($this);
        if ($this->locator === null) {
            $this->locator = [
                new ClientLocator(),
            ];
        }
        $bundleProxy->setLocator($this->locator);

        return $bundleProxy;
    }

}
