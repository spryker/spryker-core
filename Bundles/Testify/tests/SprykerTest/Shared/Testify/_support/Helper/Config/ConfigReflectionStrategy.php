<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Testify\Helper\Config;

use ArrayObject;
use ReflectionClass;
use Spryker\Shared\Config\Config;

class ConfigReflectionStrategy implements ConfigStrategyInterface
{

    /**
     * @var array
     */
    protected $configCache;

    /**
     * @return void
     */
    public function storeConfig()
    {
        Config::init();
        $reflectionProperty = $this->getConfigReflectionProperty();
        $this->configCache = $reflectionProperty->getValue()->getArrayCopy();
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
     * @param mixed $value
     *
     * @return void
     */
    public function setConfig($key, $value)
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
    public function removeConfig($key)
    {
        $configProperty = $this->getConfigReflectionProperty();
        $config = $configProperty->getValue();
        unset($config[$key]);
        $configProperty->setValue($config);
    }

    /**
     * @return void
     */
    public function resetConfig()
    {
        $reflectionProperty = $this->getConfigReflectionProperty();
        $reflectionProperty->setValue(new ArrayObject($this->configCache));
    }

}
