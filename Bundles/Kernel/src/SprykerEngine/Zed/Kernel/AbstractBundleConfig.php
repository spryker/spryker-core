<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Kernel;

use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Shared\Config;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;

abstract class AbstractBundleConfig
{

    /**
     * @var Config
     */
    private $config;

    /**
     * @var AutoCompletion|LocatorLocatorInterface
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
     * @param string $key
     *
     * @throws \Exception
     *
     * @return string
     */
    protected function get($key)
    {
        return $this->config->get($key);
    }

    /**
     * @return AutoCompletion|LocatorLocatorInterface
     */
    protected function getLocator()
    {
        return $this->locator;
    }

}
