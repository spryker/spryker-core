<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Client\Kernel;

use SprykerEngine\Client\Kernel\Service\ClientLocator;
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
     * @var Locator
     */
    private static $instance;

    /**
     * @param array $locator
     *
     * @return Locator
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
     * @param array $arguments
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
    protected function getBundleProxy()
    {
        $bundleProxy = new BundleProxy($this);
        if (is_null($this->locator)) {
            $this->locator = [
                new ClientLocator(),
            ];
        }
        $bundleProxy->setLocator($this->locator);

        return $bundleProxy;
    }

}
