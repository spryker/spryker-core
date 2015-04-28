<?php

namespace SprykerEngine\Zed\Kernel;

use SprykerEngine\Shared\Config\Config;
use SprykerEngine\Shared\Kernel\ClassResolver;
use SprykerEngine\Shared\Kernel\IdentityMapClassResolver;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerEngine\Zed\Kernel\Factory\FactoryException;
use SprykerEngine\Zed\Kernel\Factory\FactoryInterface;

abstract class AbstractBundleConfig
{

    /**
     * @var Config
     */
    private $config;

    /**
     * @var LocatorLocatorInterface
     */
    private $locator;

    /**
     * @param Config $config
     * @param LocatorLocatorInterface $locator
     */
    public function __construct(Config $config, LocatorLocatorInterface $locator)
    {
        $this->config = $config;
        $this->locator = $locator;
    }

    /**
     * @param $key
     *
     * @throws \Exception
     * @return string
     */
    protected function get($key)
    {
        return $this->config->get($key);
    }

    /**
     * @return LocatorLocatorInterface
     */
    protected function getLocator()
    {
        return $this->locator;
    }
}
