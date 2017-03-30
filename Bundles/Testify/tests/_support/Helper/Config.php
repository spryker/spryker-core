<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Testify\Helper;

use ArrayObject;
use Codeception\Module;
use Codeception\TestInterface;
use ReflectionClass;
use Spryker\Shared\Config\Config as SprykerConfig;

class Config extends Module
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
        SprykerConfig::init();
        $reflectionProperty = $this->getConfigReflectionProperty();
        $this->configCache = $reflectionProperty->getValue()->getArrayCopy();
    }

    /**
     * @return \ReflectionProperty
     */
    protected function getConfigReflectionProperty()
    {
        $reflection = new ReflectionClass(SprykerConfig::class);
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
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _after(TestInterface $test)
    {
        $reflectionProperty = $this->getConfigReflectionProperty();
        $reflectionProperty->setValue(new ArrayObject($this->configCache));
    }

}
