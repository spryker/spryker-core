<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Testify\Helper;

use ArrayObject;
use Codeception\Configuration;
use Codeception\Module;
use Codeception\Stub;
use Codeception\TestInterface;
use Exception;
use ReflectionClass;
use Spryker\Shared\Config\Config;

class ConfigHelper extends Module
{
    /**
     * @var array
     */
    protected $configCache;

    /**
     * @var \Spryker\Shared\Kernel\AbstractBundleConfig|null
     */
    protected $configStub;

    /**
     * @var array
     */
    protected $mockedConfigMethods = [];

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
     * @param string $methodName
     * @param mixed $return
     *
     * @throws \Exception
     *
     * @return object|\Spryker\Shared\Kernel\AbstractBundleConfig|null
     */
    public function mockConfigMethod(string $methodName, $return)
    {
        $className = $this->getConfigClassName();

        if (!method_exists($className, $methodName)) {
            throw new Exception(sprintf('You tried to mock a not existing method "%s". Available methods are "%s"', $methodName, implode(', ', get_class_methods($className))));
        }

        $this->mockedConfigMethods[$methodName] = $return;
        $this->configStub = Stub::make($className, $this->mockedConfigMethods);

        return $this->configStub;
    }

    /**
     * @return \Spryker\Shared\Kernel\AbstractBundleConfig
     */
    public function getModuleConfig()
    {
        if ($this->configStub !== null) {
            return $this->configStub;
        }

        $moduleConfigClassName = $this->getConfigClassName();

        return new $moduleConfigClassName();
    }

    /**
     * @return string
     */
    protected function getConfigClassName(): string
    {
        $config = Configuration::config();
        $namespaceParts = explode('\\', $config['namespace']);

        return sprintf('\%1$s\%2$s\%3$s\%3$sConfig', rtrim($namespaceParts[0], 'Test'), $namespaceParts[1], $namespaceParts[2]);
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
    public function _before(TestInterface $test)
    {
        $this->configStub = null;
        $this->mockedConfigMethods = [];
    }

    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _after(TestInterface $test)
    {
        $this->resetConfig();
    }

    /**
     * @return void
     */
    public function _afterSuite()
    {
        $this->resetConfig();
    }

    /**
     * @return void
     */
    private function resetConfig()
    {
        $reflectionProperty = $this->getConfigReflectionProperty();
        $reflectionProperty->setValue(new ArrayObject($this->configCache));
    }
}
