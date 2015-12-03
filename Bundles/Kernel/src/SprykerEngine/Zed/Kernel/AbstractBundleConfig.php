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
     * @param string $key
     *
     * @throws \Exception
     *
     * @return string
     */
    protected function get($key)
    {
        return $this->getConfig()->get($key);
    }

    /**
     * @return Config
     */
    protected function getConfig()
    {
        return Config::getInstance();
    }

    /**
     * @return AutoCompletion|LocatorLocatorInterface
     *
     * @deprecated Do not use the locator in the config anymore. If you need to configure a plugin stack, use the dependency provider for that.
     */
    protected function getLocator()
    {
        return Locator::getInstance();
    }

}
