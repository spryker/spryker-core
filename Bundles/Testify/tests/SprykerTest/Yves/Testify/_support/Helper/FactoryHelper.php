<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\Testify\Helper;

use Codeception\Configuration;
use Codeception\Module;
use Codeception\Stub;
use Codeception\TestInterface;
use Exception;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerTest\Shared\Testify\Helper\ConfigHelper;

class FactoryHelper extends Module
{
    protected const FACTORY_CLASS_NAME_PATTERN = '\%1$s\Yves\%2$s\%2$sFactory';
    protected const MODULE_NAME_POSITION = 2;

    /**
     * @var \Spryker\Yves\Kernel\AbstractFactory[]
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
     * @return \Spryker\Yves\Kernel\AbstractFactory
     */
    public function mockFactoryMethod(string $methodName, $return, ?string $moduleName = null)
    {
        $moduleName = $this->getModuleName($moduleName);
        $className = $this->getFactoryClassName($moduleName);

        if (!method_exists($className, $methodName)) {
            throw new Exception(sprintf('You tried to mock a not existing method "%s". Available methods are "%s"', $methodName, implode(', ', get_class_methods($className))));
        }

        if (!isset($this->mockedFactoryMethods[$moduleName])) {
            $this->mockedFactoryMethods[$moduleName] = [];
        }

        $this->mockedFactoryMethods[$moduleName][$methodName] = $return;
        /** @var \Spryker\Yves\Kernel\AbstractFactory $factoryStub */
        $factoryStub = Stub::make($className, $this->mockedFactoryMethods[$moduleName]);
        $this->factoryStubs[$moduleName] = $factoryStub;

        return $this->factoryStubs[$moduleName];
    }

    /**
     * @param string|null $moduleName
     *
     * @return \Spryker\Yves\Kernel\AbstractFactory|null
     */
    public function getFactory(?string $moduleName = null): AbstractFactory
    {
        $moduleName = $this->getModuleName($moduleName);

        if (isset($this->factoryStubs[$moduleName])) {
            $this->factoryStubs[$moduleName] = $this->injectConfig($this->factoryStubs[$moduleName], $moduleName);
            $this->factoryStubs[$moduleName] = $this->injectContainer($this->factoryStubs[$moduleName]);

            return $this->factoryStubs[$moduleName];
        }

        $moduleFactory = $this->createFactory($moduleName);

        $moduleFactory = $this->injectConfig($moduleFactory, $moduleName);
        $moduleFactory = $this->injectContainer($moduleFactory);

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
     * @return \Spryker\Yves\Kernel\AbstractFactory
     */
    protected function createFactory(?string $moduleName = null)
    {
        $moduleName = $this->getModuleName($moduleName);
        $moduleFactoryClassName = $this->getFactoryClassName($moduleName);

        return new $moduleFactoryClassName();
    }

    /**
     * @param string $moduleName
     *
     * @return string
     */
    protected function getFactoryClassName(string $moduleName): string
    {
        $config = Configuration::config();
        $namespaceParts = explode('\\', $config['namespace']);

        return sprintf(static::FACTORY_CLASS_NAME_PATTERN, rtrim($namespaceParts[0], 'Test'), $moduleName);
    }

    /**
     * @param \Spryker\Yves\Kernel\AbstractFactory $factory
     * @param string $moduleName
     *
     * @return \Spryker\Yves\Kernel\AbstractFactory
     */
    protected function injectConfig(AbstractFactory $factory, string $moduleName)
    {
        if ($this->hasModule('\\' . ConfigHelper::class)) {
            $factory->setConfig($this->getConfig($moduleName));
        }

        return $factory;
    }

    /**
     * @param string $moduleName
     *
     * @return \Spryker\Yves\Kernel\AbstractBundleConfig
     */
    protected function getConfig(string $moduleName)
    {
        /** @var \Spryker\Yves\Kernel\AbstractBundleConfig $moduleConfig */
        $moduleConfig = $this->getConfigHelper()->getModuleConfig($moduleName);

        return $moduleConfig;
    }

    /**
     * @return \SprykerTest\Shared\Testify\Helper\ConfigHelper
     */
    protected function getConfigHelper(): ConfigHelper
    {
        /** @var \SprykerTest\Shared\Testify\Helper\ConfigHelper $configHelper */
        $configHelper = $this->getModule('\\' . ConfigHelper::class);

        return $configHelper;
    }

    /**
     * @param \Spryker\Yves\Kernel\AbstractFactory $factory
     *
     * @return \Spryker\Yves\Kernel\AbstractFactory
     */
    protected function injectContainer(AbstractFactory $factory)
    {
        if ($this->hasModule('\\' . DependencyProviderHelper::class)) {
            $factory->setContainer($this->getContainer());
        }

        return $factory;
    }

    /**
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function getContainer(): Container
    {
        return $this->getDependencyProviderHelper()->getContainer();
    }

    /**
     * @return \SprykerTest\Yves\Testify\Helper\DependencyProviderHelper
     */
    protected function getDependencyProviderHelper(): DependencyProviderHelper
    {
        /** @var \SprykerTest\Yves\Testify\Helper\DependencyProviderHelper $dependencyProviderHelper */
        $dependencyProviderHelper = $this->getModule('\\' . DependencyProviderHelper::class);

        return $dependencyProviderHelper;
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
