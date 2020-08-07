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
use ReflectionProperty;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Kernel\AbstractBundleConfig;
use Spryker\Shared\Kernel\AbstractSharedConfig;
use SprykerTest\Shared\Testify\ClassResolver\ClassResolverTrait;

class ConfigHelper extends Module
{
    use ClassResolverTrait;

    protected const CONFIG_CLASS_NAME_PATTERN = '\%1$s\%2$s\%3$s\%3$sConfig';
    protected const SHARED_CONFIG_CLASS_NAME_PATTERN = '\%1$s\Shared\%3$s\%3$sConfig';
    protected const MODULE_NAME_POSITION = 2;

    /**
     * @var array
     */
    protected $configCache;

    /**
     * @var \Spryker\Shared\Kernel\AbstractBundleConfig[]
     */
    protected $configStubs = [];

    /**
     * @var array
     */
    protected $mockedConfigMethods = [];

    /**
     * @var \Spryker\Shared\Kernel\AbstractSharedConfig[]
     */
    protected $sharedConfigStubs = [];

    /**
     * @var array
     */
    protected $mockedSharedConfigMethods = [];

    /**
     * @return void
     */
    public function _initialize(): void
    {
        Config::init();
        $reflectionProperty = $this->getConfigReflectionProperty();
        $this->configCache = $reflectionProperty->getValue()->getArrayCopy();
    }

    /**
     * @return \ReflectionProperty
     */
    protected function getConfigReflectionProperty(): ReflectionProperty
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
    public function setConfig(string $key, $value): void
    {
        $configProperty = $this->getConfigReflectionProperty();
        $config = $configProperty->getValue();
        $config[$key] = $value;
        $configProperty->setValue($config);
    }

    /**
     * @param string $key
     * @param array|bool|float|int|string $value
     *
     * @return void
     */
    public function mockEnvironmentConfig(string $key, $value): void
    {
        $configProperty = $this->getConfigReflectionProperty();
        $config = $configProperty->getValue();
        $config[$key] = $value;
        $configProperty->setValue($config);
    }

    /**
     * @param string $methodName
     * @param mixed $return
     * @param string|null $moduleName
     *
     * @throws \Exception
     *
     * @return \Spryker\Shared\Kernel\AbstractBundleConfig|null
     */
    public function mockConfigMethod(string $methodName, $return, ?string $moduleName = null): ?AbstractBundleConfig
    {
        $moduleName = $this->getModuleName($moduleName);
        $className = $this->resolveClassName(static::CONFIG_CLASS_NAME_PATTERN, $moduleName);

        if (!method_exists($className, $methodName)) {
            throw new Exception(sprintf('You tried to mock a not existing method "%s". Available methods are "%s"', $methodName, implode(', ', get_class_methods($className))));
        }

        if (!isset($this->mockedConfigMethods[$moduleName])) {
            $this->mockedConfigMethods[$moduleName] = [];
        }

        $this->mockedConfigMethods[$moduleName][$methodName] = $return;

        /** @var \Spryker\Shared\Kernel\AbstractBundleConfig $configStub */
        $configStub = Stub::make($className, $this->mockedConfigMethods[$moduleName]);
        $this->configStubs[$moduleName] = $configStub;

        return $this->configStubs[$moduleName];
    }

    /**
     * @param string|null $moduleName
     *
     * @return string
     */
    protected function getModuleName(?string $moduleName = null): string
    {
        if ($moduleName) {
            return $moduleName;
        }

        $config = Configuration::config();
        $namespaceParts = explode('\\', $config['namespace']);

        return $namespaceParts[static::MODULE_NAME_POSITION];
    }

    /**
     * @param string $methodName
     * @param mixed $return
     * @param string|null $moduleName
     *
     * @throws \Exception
     *
     * @return \Spryker\Shared\Kernel\AbstractSharedConfig|null
     */
    public function mockSharedConfigMethod(string $methodName, $return, ?string $moduleName = null): ?AbstractSharedConfig
    {
        $moduleName = $this->getModuleName($moduleName);
        $className = $this->resolveClassName(static::SHARED_CONFIG_CLASS_NAME_PATTERN, $moduleName);

        if (!method_exists($className, $methodName)) {
            throw new Exception(sprintf('You tried to mock a not existing method "%s". Available methods are "%s"', $methodName, implode(', ', get_class_methods($className))));
        }

        if (!isset($this->mockedSharedConfigMethods[$moduleName])) {
            $this->mockedSharedConfigMethods[$moduleName] = [];
        }

        $this->mockedSharedConfigMethods[$moduleName][$methodName] = $return;

        /** @var \Spryker\Shared\Kernel\AbstractSharedConfig $sharedConfigStub */
        $sharedConfigStub = Stub::make($className, $this->mockedSharedConfigMethods[$moduleName]);
        $this->sharedConfigStubs[$moduleName] = $sharedConfigStub;

        return $this->sharedConfigStubs[$moduleName];
    }

    /**
     * @param string|null $moduleName
     *
     * @return \Spryker\Shared\Kernel\AbstractBundleConfig
     */
    public function getModuleConfig(?string $moduleName = null): AbstractBundleConfig
    {
        $moduleName = $this->getModuleName($moduleName);

        if (isset($this->configStubs[$moduleName])) {
            $this->configStubs[$moduleName] = $this->injectSharedConfig($this->configStubs[$moduleName], $moduleName);

            return $this->configStubs[$moduleName];
        }

        $moduleConfig = $this->createConfig($moduleName);
        $moduleConfig = $this->injectSharedConfig($moduleConfig, $moduleName);

        return $moduleConfig;
    }

    /**
     * @param string|null $moduleName
     *
     * @return \Spryker\Shared\Kernel\AbstractSharedConfig|null
     */
    public function getSharedModuleConfig(?string $moduleName = null): ?AbstractSharedConfig
    {
        $moduleName = $this->getModuleName($moduleName);

        if (isset($this->sharedConfigStubs[$moduleName])) {
            return $this->sharedConfigStubs[$moduleName];
        }

        $sharedConfig = $this->createSharedConfig($moduleName);

        return $sharedConfig;
    }

    /**
     * @param string $moduleName
     *
     * @return \Spryker\Yves\Kernel\AbstractBundleConfig|\Spryker\Zed\Kernel\AbstractBundleConfig|\Spryker\Glue\Kernel\AbstractBundleConfig|\Spryker\Client\Kernel\AbstractBundleConfig
     */
    protected function createConfig(string $moduleName)
    {
        $moduleConfigClassName = $this->resolveClass(static::CONFIG_CLASS_NAME_PATTERN, $moduleName);

        return new $moduleConfigClassName();
    }

    /**
     * @param \Spryker\Shared\Kernel\AbstractBundleConfig $moduleConfig
     * @param string $moduleName
     *
     * @return \Spryker\Shared\Kernel\AbstractBundleConfig
     */
    protected function injectSharedConfig(AbstractBundleConfig $moduleConfig, string $moduleName): AbstractBundleConfig
    {
        if (!method_exists($moduleConfig, 'setSharedConfig')) {
            return $moduleConfig;
        }

        $sharedConfig = $this->getSharedConfig($moduleName);
        if ($sharedConfig === null) {
            return $moduleConfig;
        }

        $moduleConfig->setSharedConfig($sharedConfig);

        return $moduleConfig;
    }

    /**
     * @param string $moduleName
     *
     * @return \Spryker\Shared\Kernel\AbstractSharedConfig|null
     */
    protected function getSharedConfig(string $moduleName): ?AbstractSharedConfig
    {
        if (isset($this->sharedConfigStubs[$moduleName])) {
            return $this->sharedConfigStubs[$moduleName];
        }

        return $this->createSharedConfig($moduleName);
    }

    /**
     * @param string $moduleName
     *
     * @return \Spryker\Shared\Kernel\AbstractSharedConfig|null
     */
    protected function createSharedConfig(string $moduleName): ?AbstractSharedConfig
    {
        $sharedConfigClass = $this->resolveClass(static::SHARED_CONFIG_CLASS_NAME_PATTERN, $moduleName);

        return $sharedConfigClass;
    }

    /**
     * @param string $key
     *
     * @return void
     */
    public function removeConfig(string $key): void
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
    public function _before(TestInterface $test): void
    {
        $this->configStubs = [];
        $this->mockedConfigMethods = [];

        $this->sharedConfigStubs = [];
        $this->mockedSharedConfigMethods = [];
    }

    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _after(TestInterface $test): void
    {
        $this->resetConfig();
        $this->configStubs = [];
        $this->mockedConfigMethods = [];
        $this->sharedConfigStubs = [];
        $this->mockedSharedConfigMethods = [];
    }

    /**
     * @return void
     */
    public function _afterSuite(): void
    {
        $this->resetConfig();
    }

    /**
     * @return void
     */
    private function resetConfig(): void
    {
        $reflectionProperty = $this->getConfigReflectionProperty();
        $reflectionProperty->setValue(new ArrayObject($this->configCache));
    }
}
