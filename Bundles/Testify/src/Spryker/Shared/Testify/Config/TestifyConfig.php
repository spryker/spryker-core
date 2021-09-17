<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Testify\Config;

use ReflectionClass;
use Spryker\Shared\Config\Config;

class TestifyConfig implements TestifyConfigInterface
{
    /**
     * @var array
     */
    protected $configCache;

    public function __construct()
    {
        $reflectionProperty = $this->getConfigReflectionProperty();
        $this->configCache = $reflectionProperty->getValue();
    }

    /**
     * @return \ReflectionProperty
     */
    protected function getConfigReflectionProperty()
    {
        $reflection = new ReflectionClass(Config::class);
        $reflectionProperty = $reflection->getProperty('config');
        $reflectionProperty->setAccessible(true);

        return $reflectionProperty;
    }

    /**
     * @param string $key
     * @param array|string|float|int|bool $value
     *
     * @return void
     */
    public function set($key, $value)
    {
        $configProperty = $this->getConfigReflectionProperty();
        $config = $configProperty->getValue();
        $config[$key] = $value;
        $configProperty->setValue($config);
    }

    /**
     * @param string $key
     *
     * @return void
     */
    public function remove($key)
    {
        $configProperty = $this->getConfigReflectionProperty();
        $config = $configProperty->getValue();
        unset($config[$key]);
        $configProperty->setValue($config);
    }

    public function __destruct()
    {
        $reflectionProperty = $this->getConfigReflectionProperty();
        $reflectionProperty->setValue($this->configCache);
    }
}
