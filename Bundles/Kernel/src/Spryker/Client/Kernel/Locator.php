<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Kernel;

use Spryker\Shared\Kernel\BundleProxy;
use Spryker\Shared\Kernel\LocatorLocatorInterface;

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
     * @return self
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
     * @return \Spryker\Shared\Kernel\BundleProxy
     */
    public function __call($bundle, array $arguments = null)
    {
        if ($this->bundleProxy === null) {
            $this->bundleProxy = $this->getBundleProxy();
        }

        return $this->bundleProxy->setBundle($bundle);
    }

    /**
     * @return \Spryker\Shared\Kernel\BundleProxy
     */
    protected function getBundleProxy()
    {
        $bundleProxy = new BundleProxy();
        if ($this->locator === null) {
            $this->locator = [
                new ClientLocator(),
            ];
        }
        $bundleProxy->setLocator($this->locator);

        return $bundleProxy;
    }

}
