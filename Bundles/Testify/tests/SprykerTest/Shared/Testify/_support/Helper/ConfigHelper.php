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
use Spryker\Shared\Kernel\AbstractBundleConfig;

class ConfigHelper extends Module
{
    protected const CONFIG_CLASS_NAME_PATTERN = '\%1$s\%2$s\%3$s\%3$sConfig';

    /**
     * @var array
     */
    protected $configCache;

    /**
     * @var \Spryker\Shared\Kernel\AbstractBundleConfig|object|null
     */
    protected $configStub;

    /**
     * @var array
     */
    protected $mockedConfigMethods = [];

    /**
     * @var \Spryker\Shared\Kernel\AbstractBundleConfig|object|null
     */
    protected $sharedConfigStub;

    /**
     * @var array
     */
    protected $mockedSharedConfigMethods = [];

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
     * @param string $methodName
     * @param mixed $return
     *
     * @throws \Exception
     *
     * @return object|\Spryker\Shared\Kernel\AbstractBundleConfig|null
     */
    public function mockSharedConfigMethod(string $methodName, $return)
    {
        $className = $this->getSharedConfigClassName();

        if (!method_exists($className, $methodName)) {
            throw new Exception(sprintf('You tried to mock a not existing method "%s". Available methods are "%s"', $methodName, implode(', ', get_class_methods($className))));
        }

        $this->mockedSharedConfigMethods[$methodName] = $return;
        $this->sharedConfigStub = Stub::make($className, $this->mockedSharedConfigMethods);

        return $this->sharedConfigStub;
    }

    /**
     * @return \Spryker\Shared\Kernel\AbstractBundleConfig
     */
    public function getModuleConfig()
    {
        if ($this->configStub !== null) {
            $this->configStub = $this->injectSharedConfig($this->configStub);

            return $this->configStub;
        }

        $moduleConfig = $this->createConfig();
        $moduleConfig = $this->injectSharedConfig($moduleConfig);

        return $moduleConfig;
    }

    /**
     * @return \Spryker\Yves\Kernel\AbstractBundleConfig|\Spryker\Zed\Kernel\AbstractBundleConfig|\Spryker\Glue\Kernel\AbstractBundleConfig|\Spryker\Client\Kernel\AbstractBundleConfig
     */
    protected function createConfig()
    {
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

        return sprintf(static::CONFIG_CLASS_NAME_PATTERN, rtrim($namespaceParts[0], 'Test'), $namespaceParts[1], $namespaceParts[2]);
    }

    /**
     * @param \Spryker\Shared\Kernel\AbstractBundleConfig $moduleConfig
     *
     * @return \Spryker\Shared\Kernel\AbstractBundleConfig
     */
    protected function injectSharedConfig(AbstractBundleConfig $moduleConfig)
    {
        if (!method_exists($moduleConfig, 'setSharedConfig')) {
            return $moduleConfig;
        }

        $sharedConfig = $this->getSharedConfig();
        if ($sharedConfig === null) {
            return $moduleConfig;
        }

        $moduleConfig->setSharedConfig($sharedConfig);

        return $moduleConfig;
    }

    /**
     * @return \Spryker\Shared\Kernel\AbstractBundleConfig|null
     */
    protected function getSharedConfig()
    {
        if ($this->sharedConfigStub !== null) {
            return $this->sharedConfigStub;
        }

        return $this->createSharedConfig();
    }

    /**
     * @return \Spryker\Shared\Kernel\AbstractBundleConfig|null
     */
    protected function createSharedConfig()
    {
        $sharedConfigClassName = $this->getSharedConfigClassName();
        if (!class_exists($sharedConfigClassName)) {
            return null;
        }

        return new $sharedConfigClassName();
    }

    /**
     * @return string
     */
    protected function getSharedConfigClassName(): string
    {
        $config = Configuration::config();
        $namespaceParts = explode('\\', $config['namespace']);

        return sprintf('\%1$s\Shared\%2$s\%2$sConfig', rtrim($namespaceParts[0], 'Test'), $namespaceParts[2]);
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

        $this->sharedConfigStub = null;
        $this->mockedSharedConfigMethods = [];
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
