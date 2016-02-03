<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Kernel;

use Spryker\Shared\Config;

abstract class AbstractBundleConfig
{

    /**
     * @param string $key
     *
     * @throws \Exception
     *
     * @return mixed
     */
    protected function get($key)
    {
        return $this->getConfig()->get($key);
    }

    /**
     * @return \Spryker\Shared\Config
     */
    protected function getConfig()
    {
        return Config::getInstance();
    }

}
