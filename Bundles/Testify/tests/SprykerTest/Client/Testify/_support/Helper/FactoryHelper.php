<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Testify\Helper;

use Codeception\Configuration;
use Codeception\Module;
use Codeception\Stub;
use Codeception\TestInterface;
use Exception;
use Spryker\Client\Kernel\AbstractBundleConfig;
use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Kernel\Container;
use SprykerTest\Shared\Testify\Helper\ClassResolverTrait;
use SprykerTest\Shared\Testify\Helper\ConfigHelper;
use SprykerTest\Shared\Testify\Helper\ConfigHelperTrait;

class FactoryHelper extends Module
{
    use DependencyProviderHelperTrait;
    use ConfigHelperTrait;
    use ClassResolverTrait;

    protected const FACTORY_CLASS_NAME_PATTERN = '\%1$s\Client\%3$s\%3$sFactory';
    protected const MODULE_NAME_POSITION = 2;

    /**
     * @var \Spryker\Client\Kernel\AbstractFactory[]
     */
    protected $factoryStubs = [];

    /**
     * @var array
     */
    protected $mockedFactoryMethods = [];

    /**
     * @param string $methodName
     * @param mixed $return
     * @param string|null $moduleName
     *
     * @throws \Exception
     *
     * @return \Spryker\Client\Kernel\AbstractFactory
     */
    public function mockFactoryMethod(string $methodName, $return, ?string $moduleName = null): AbstractFactory
    {
        $moduleName = $this->getModuleName($moduleName);
        $className = $this->resolveClassName(static::FACTORY_CLASS_NAME_PATTERN, $moduleName);

        if (!method_exists($className, $methodName)) {
            throw new Exception(sprintf('You tried to mock a not existing method "%s". Available methods are "%s"', $methodName, implode(', ', get_class_methods($className))));
        }

        if (!isset($this->mockedFactoryMethods[$moduleName])) {
            $this->mockedFactoryMethods[$moduleName] = [];
        }

        $this->mockedFactoryMethods[$moduleName][$methodName] = $return;
        /** @var \Spryker\Client\Kernel\AbstractFactory $factoryStub */
        $factoryStub = Stub::make($className, $this->mockedFactoryMethods[$moduleName]);
        $this->factoryStubs[$moduleName] = $factoryStub;

        return $this->factoryStubs[$moduleName];
    }

    /**
     * @param string|null $moduleName
     *
     * @return \Spryker\Client\Kernel\AbstractFactory
     */
    public function getFactory(?string $moduleName = null): AbstractFactory
    {
        $moduleName = $this->getModuleName($moduleName);

        if (isset($this->factoryStubs[$moduleName])) {
            $this->factoryStubs[$moduleName] = $this->injectConfig($this->factoryStubs[$moduleName], $moduleName);
            $this->factoryStubs[$moduleName] = $this->injectContainer($this->factoryStubs[$moduleName], $moduleName);

            return $this->factoryStubs[$moduleName];
        }

        $moduleFactory = $this->createFactory($moduleName);

        $moduleFactory = $this->injectConfig($moduleFactory, $moduleName);
        $moduleFactory = $this->injectContainer($moduleFactory, $moduleName);

        return $moduleFactory;
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
     * @param string|null $moduleName
     *
     * @return \Spryker\Client\Kernel\AbstractFactory
     */
    protected function createFactory(?string $moduleName = null): AbstractFactory
    {
        $moduleName = $this->getModuleName($moduleName);
        $moduleFactoryClassName = $this->resolveClassName(static::FACTORY_CLASS_NAME_PATTERN, $moduleName);

        return new $moduleFactoryClassName();
    }

    /**
     * @param \Spryker\Client\Kernel\AbstractFactory $factory
     * @param string $moduleName
     *
     * @return \Spryker\Client\Kernel\AbstractFactory
     */
    protected function injectConfig(AbstractFactory $factory, string $moduleName): AbstractFactory
    {
        if ($this->hasModule('\\' . ConfigHelper::class)) {
            $factory->setConfig($this->getConfig($moduleName));
        }

        return $factory;
    }

    /**
     * @param string $moduleName
     *
     * @return \Spryker\Client\Kernel\AbstractBundleConfig
     */
    protected function getConfig(string $moduleName): AbstractBundleConfig
    {
        /** @var \Spryker\Client\Kernel\AbstractBundleConfig $moduleConfig */
        $moduleConfig = $this->getConfigHelper()->getModuleConfig($moduleName);

        return $moduleConfig;
    }

    /**
     * @param \Spryker\Client\Kernel\AbstractFactory $factory
     * @param string $moduleName
     *
     * @return \Spryker\Client\Kernel\AbstractFactory
     */
    protected function injectContainer(AbstractFactory $factory, string $moduleName): AbstractFactory
    {
        if ($this->hasModule('\\' . DependencyProviderHelper::class)) {
            $factory->setContainer($this->getContainer($moduleName));
        }

        return $factory;
    }

    /**
     * @param string $moduleName
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function getContainer(string $moduleName): Container
    {
        return $this->getDependencyProviderHelper()->getModuleContainer($moduleName);
    }

    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _before(TestInterface $test): void
    {
        $this->factoryStubs = [];
        $this->mockedFactoryMethods = [];
    }
}
