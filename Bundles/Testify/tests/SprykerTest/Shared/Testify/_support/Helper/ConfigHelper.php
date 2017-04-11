<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Testify\Helper;

use ArrayObject;
use Codeception\Module;
use Codeception\TestCase;
use ReflectionClass;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Kernel\KernelConstants;

class ConfigHelper extends Module
{

    /**
     * @var array
     */
    protected $configCache;

    /**
     * @return void
     */
    public function _initialize()
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
     * @param array|bool|float|int|string $value
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
     * @param \Codeception\TestCase $test
     *
     * @return void
     */
    public function _after(TestCase $test)
    {
        $reflectionProperty = $this->getConfigReflectionProperty();
        $reflectionProperty->setValue(new ArrayObject($this->configCache));
    }

}
