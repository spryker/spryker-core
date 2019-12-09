<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Testify\Helper\Business;

use Codeception\Configuration;
use Codeception\Module;
use Codeception\Stub;
use Codeception\TestInterface;
use Exception;
use Spryker\Shared\Kernel\AbstractBundleConfig as AbstractSharedConfig;
use Spryker\Shared\Kernel\AbstractSharedFactory;
use Spryker\Zed\Kernel\AbstractBundleConfig;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Kernel\Business\AbstractFacade;
use Spryker\Zed\Kernel\Container;
use SprykerTest\Shared\Testify\Helper\ConfigHelper;
use SprykerTest\Shared\Testify\Helper\ConfigHelperTrait;
use SprykerTest\Zed\Testify\Helper\Communication\DependencyProviderHelper;

class BusinessHelper extends Module
{
    use ConfigHelperTrait;
    use DependencyProviderHelperTrait;

    protected const BUSINESS_FACTORY_CLASS_NAME_PATTERN = '\%1$s\%2$s\%3$s\Business\%3$sBusinessFactory';
    protected const BUSINESS_SHARED_FACTORY_CLASS_NAME_PATTERN = '\%1$s\Shared\%2$s\%2$sSharedFactory';
    protected const BUSINESS_FACADE_CLASS_NAME_PATTERN = '\%1$s\%2$s\%3$s\Business\%3$sFacade';

    /**
     * @var array
     */
    protected $dependencies = [];

    /**
     * @var \Spryker\Zed\Kernel\Business\AbstractFacade[]
     */
    protected $facadeStubs = [];

    /**
     * @var array
     */
    protected $mockedFacadeMethods = [];

    /**
     * @var \Spryker\Zed\Kernel\Business\AbstractBusinessFactory[]
     */
    protected $factoryStubs = [];

    /**
     * @var array
     */
    protected $mockedFactoryMethods = [];

    /**
     * @var \Spryker\Shared\Kernel\AbstractSharedFactory[]
     */
    protected $sharedFactoryStubs = [];

    /**
     * @var array
     */
    protected $mockedSharedFactoryMethods = [];

    /**
     * @param string $methodName
     * @param mixed $return
     * @param string|null $moduleName
     *
     * @throws \Exception
     *
     * @return \Spryker\Zed\Kernel\Business\AbstractFacade
     */
    public function mockFacadeMethod(string $methodName, $return, ?string $moduleName = null)
    {
        $moduleName = $this->getModuleName($moduleName);
        $className = $this->getFacadeClassName($moduleName);

        if (!method_exists($className, $methodName)) {
            throw new Exception(sprintf('You tried to mock a not existing method "%s". Available methods are "%s"', $methodName, implode(', ', get_class_methods($className))));
        }

        if (!isset($this->mockedFacadeMethods[$moduleName])) {
            $this->mockedFacadeMethods[$moduleName] = [];
        }

        $this->mockedFacadeMethods[$moduleName][$methodName] = $return;
        /** @var \Spryker\Zed\Kernel\Business\AbstractFacade $facadeStub */
        $facadeStub = Stub::make($className, $this->mockedFacadeMethods[$moduleName]);
        $this->facadeStubs[$moduleName] = $facadeStub;

        return $this->facadeStubs[$moduleName];
    }

    /**
     * @param string|null $moduleName
     *
     * @return \Spryker\Zed\Kernel\Business\AbstractFacade
     */
    public function getFacade(?string $moduleName = null): AbstractFacade
    {
        $moduleName = $this->getModuleName($moduleName);

        if (!isset($this->facadeStubs[$moduleName])) {
            $this->facadeStubs[$moduleName] = $this->createFacade($moduleName);
        }

        $this->facadeStubs[$moduleName] = $this->injectFactory($this->facadeStubs[$moduleName], $moduleName);

        return $this->facadeStubs[$moduleName];
    }

    /**
     * @param \Spryker\Zed\Kernel\Business\AbstractFacade $facade
     * @param string $moduleName
     *
     * @return \Spryker\Zed\Kernel\Business\AbstractFacade
     */
    protected function injectFactory(AbstractFacade $facade, string $moduleName): AbstractFacade
    {
        $facade->setFactory($this->getFactory($moduleName));

        return $facade;
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

        return $namespaceParts[2];
    }

    /**
     * @param string $moduleName
     *
     * @return \Spryker\Zed\Kernel\Business\AbstractFacade
     */
    protected function createFacade(string $moduleName): AbstractFacade
    {
        $className = $this->getFacadeClassName($moduleName);

        return new $className();
    }

    /**
     * @param string $moduleName
     *
     * @return string
     */
    protected function getFacadeClassName(string $moduleName): string
    {
        $config = Configuration::config();
        $namespaceParts = explode('\\', $config['namespace']);

        return sprintf(static::BUSINESS_FACADE_CLASS_NAME_PATTERN, rtrim($namespaceParts[0], 'Test'), $namespaceParts[1], $moduleName);
    }

    /**
     * @param string $methodName
     * @param mixed $return
     * @param string|null $moduleName
     *
     * @throws \Exception
     *
     * @return object|\Spryker\Zed\Kernel\Business\AbstractBusinessFactory
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
        /** @var \Spryker\Zed\Kernel\Business\AbstractBusinessFactory $factoryStub */
        $factoryStub = Stub::make($className, $this->mockedFactoryMethods[$moduleName]);
        $this->factoryStubs[$moduleName] = $factoryStub;

        return $this->factoryStubs[$moduleName];
    }

    /**
     * @param string $methodName
     * @param mixed $return
     * @param string|null $moduleName
     *
     * @throws \Exception
     *
     * @return object|\Spryker\Zed\Kernel\Business\AbstractBusinessFactory
     */
    public function mockSharedFactoryMethod(string $methodName, $return, ?string $moduleName = null)
    {
        $moduleName = $this->getModuleName($moduleName);
        $className = $this->getSharedFactoryClassName($moduleName);

        if (!method_exists($className, $methodName)) {
            throw new Exception(sprintf('You tried to mock a not existing method "%s". Available methods are "%s"', $methodName, implode(', ', get_class_methods($className))));
        }

        if (!isset($this->mockedSharedFactoryMethods[$moduleName])) {
            $this->mockedSharedFactoryMethods[$moduleName] = [];
        }

        $this->mockedSharedFactoryMethods[$moduleName][$methodName] = $return;
        /** @var \Spryker\Shared\Kernel\AbstractSharedFactory $sharedFactoryStub */
        $sharedFactoryStub = Stub::make($className, $this->mockedSharedFactoryMethods[$moduleName]);
        $this->sharedFactoryStubs[$moduleName] = $sharedFactoryStub;

        return $this->sharedFactoryStubs[$moduleName];
    }

    /**
     * @param string|null $moduleName
     *
     * @return \Spryker\Zed\Kernel\Business\AbstractBusinessFactory
     */
    public function getFactory(?string $moduleName = null): AbstractBusinessFactory
    {
        $moduleName = $this->getModuleName($moduleName);

        if (isset($this->factoryStubs[$moduleName])) {
            $this->factoryStubs[$moduleName] = $this->injectConfig($this->factoryStubs[$moduleName], $moduleName);
            $this->factoryStubs[$moduleName] = $this->injectContainer($this->factoryStubs[$moduleName], $moduleName);
            $this->factoryStubs[$moduleName] = $this->injectSharedFactory($this->factoryStubs[$moduleName], $moduleName);

            return $this->factoryStubs[$moduleName];
        }

        $moduleFactory = $this->createFactory($moduleName);

        $moduleFactory = $this->injectConfig($moduleFactory, $moduleName);
        $moduleFactory = $this->injectContainer($moduleFactory, $moduleName);
        $moduleFactory = $this->injectSharedFactory($moduleFactory, $moduleName);

        return $moduleFactory;
    }

    /**
     * @param string $moduleName
     *
     * @return \Spryker\Zed\Kernel\Business\AbstractBusinessFactory
     */
    protected function createFactory(string $moduleName): AbstractBusinessFactory
    {
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

        return sprintf(static::BUSINESS_FACTORY_CLASS_NAME_PATTERN, rtrim($namespaceParts[0], 'Test'), $namespaceParts[1], $moduleName);
    }

    /**
     * @param \Spryker\Zed\Kernel\Business\AbstractBusinessFactory $businessFactory
     * @param string $moduleName
     *
     * @return \Spryker\Zed\Kernel\Business\AbstractBusinessFactory
     */
    protected function injectConfig(AbstractBusinessFactory $businessFactory, string $moduleName): AbstractBusinessFactory
    {
        if ($this->hasModule('\\' . ConfigHelper::class)) {
            $businessFactory->setConfig($this->getConfig($moduleName));
        }

        return $businessFactory;
    }

    /**
     * @param string $moduleName
     *
     * @return \Spryker\Zed\Kernel\AbstractBundleConfig
     */
    protected function getConfig(string $moduleName): AbstractBundleConfig
    {
        /** @var \Spryker\Zed\Kernel\AbstractBundleConfig $config */
        $config = $this->getConfigHelper()->getModuleConfig($moduleName);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Kernel\Business\AbstractBusinessFactory $factory
     * @param string $moduleName
     *
     * @return \Spryker\Zed\Kernel\Business\AbstractBusinessFactory
     */
    protected function injectContainer(AbstractBusinessFactory $factory, string $moduleName): AbstractBusinessFactory
    {
        if ($this->hasModule('\\' . DependencyProviderHelper::class)) {
            $factory->setContainer($this->getContainer($moduleName));
        }

        return $factory;
    }

    /**
     * @param \Spryker\Zed\Kernel\Business\AbstractBusinessFactory $factory
     * @param string $moduleName
     *
     * @return \Spryker\Zed\Kernel\Business\AbstractBusinessFactory
     */
    protected function injectSharedFactory(AbstractBusinessFactory $factory, string $moduleName): AbstractBusinessFactory
    {
        $sharedFactory = $this->getSharedFactory($moduleName);

        if ($sharedFactory !== null) {
            $factory->setSharedFactory($sharedFactory);
        }

        return $factory;
    }

    /**
     * @param string $moduleName
     *
     * @return \Spryker\Shared\Kernel\AbstractSharedFactory|null
     */
    protected function getSharedFactory(string $moduleName): ?AbstractSharedFactory
    {
        if (isset($this->sharedFactoryStubs[$moduleName])) {
            $this->sharedFactoryStubs[$moduleName] = $this->injectSharedConfig($this->sharedFactoryStubs[$moduleName], $moduleName);

            return $this->sharedFactoryStubs[$moduleName];
        }

        $sharedModuleFactory = $this->createSharedFactory($moduleName);

        if ($sharedModuleFactory === null) {
            return null;
        }

        $sharedModuleFactory = $this->injectSharedConfig($sharedModuleFactory, $moduleName);

        return $sharedModuleFactory;
    }

    /**
     * @param string $moduleName
     *
     * @return \Spryker\Shared\Kernel\AbstractSharedFactory|null
     */
    protected function createSharedFactory(string $moduleName): ?AbstractSharedFactory
    {
        $moduleFactoryClassName = $this->getSharedFactoryClassName($moduleName);

        if (!class_exists($moduleFactoryClassName)) {
            return null;
        }

        return new $moduleFactoryClassName();
    }

    /**
     * @param string $moduleName
     *
     * @return string
     */
    protected function getSharedFactoryClassName(string $moduleName): string
    {
        $config = Configuration::config();
        $namespaceParts = explode('\\', $config['namespace']);

        return sprintf(static::BUSINESS_SHARED_FACTORY_CLASS_NAME_PATTERN, rtrim($namespaceParts[0], 'Test'), $moduleName);
    }

    /**
     * @param \Spryker\Shared\Kernel\AbstractSharedFactory $sharedFactory
     * @param string $moduleName
     *
     * @return \Spryker\Shared\Kernel\AbstractSharedFactory
     */
    protected function injectSharedConfig(AbstractSharedFactory $sharedFactory, string $moduleName): AbstractSharedFactory
    {
        if ($this->hasModule('\\' . ConfigHelper::class)) {
            $sharedModuleConfig = $this->getSharedModuleConfig($moduleName);

            if ($sharedModuleConfig === null) {
                return $sharedFactory;
            }

            $sharedFactory->setSharedConfig($sharedModuleConfig);
        }

        return $sharedFactory;
    }

    /**
     * @param string $moduleName
     *
     * @return \Spryker\Shared\Kernel\AbstractBundleConfig|null
     */
    protected function getSharedModuleConfig(string $moduleName): ?AbstractSharedConfig
    {
        /** @var \Spryker\Shared\Kernel\AbstractBundleConfig $sharedModuleConfig */
        $sharedModuleConfig = $this->getConfigHelper()->getSharedModuleConfig($moduleName);

        return $sharedModuleConfig;
    }

    /**
     * @param string $moduleName
     *
     * @return \Spryker\Zed\Kernel\Container
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

        $this->facadeStubs = [];
        $this->mockedFacadeMethods = [];
    }
}
