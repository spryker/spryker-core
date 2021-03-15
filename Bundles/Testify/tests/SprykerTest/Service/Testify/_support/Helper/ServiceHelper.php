<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\Testify\Helper;

use Codeception\Module;
use Codeception\Stub;
use Codeception\TestInterface;
use Exception;
use Spryker\Service\Kernel\AbstractBundleConfig;
use Spryker\Service\Kernel\AbstractService;
use Spryker\Service\Kernel\AbstractServiceFactory;
use Spryker\Service\Kernel\Container;
use SprykerTest\Shared\Testify\Helper\ClassResolverTrait;
use SprykerTest\Shared\Testify\Helper\ConfigHelper;
use SprykerTest\Shared\Testify\Helper\ConfigHelperTrait;
use SprykerTest\Shared\Testify\Helper\ModuleNameTrait;
use Throwable;

class ServiceHelper extends Module
{
    use ModuleNameTrait;
    use ConfigHelperTrait;
    use ClassResolverTrait;
    use DependencyProviderHelperTrait;

    protected const SERVICE_FACTORY_CLASS_NAME_PATTERN = '\%1$s\%2$s\%3$s\%3$sServiceFactory';
    protected const SERVICE_CLASS_NAME_PATTERN = '\%1$s\%2$s\%3$s\%3$sService';

    /**
     * @var array
     */
    protected $dependencies = [];

    /**
     * @var \Spryker\Service\Kernel\AbstractService[]
     */
    protected $serviceStubs = [];

    /**
     * @var array
     */
    protected $mockedServiceMethods = [];

    /**
     * @var \Spryker\Service\Kernel\AbstractServiceFactory[]
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
     * @return \Spryker\Service\Kernel\AbstractService
     */
    public function mockServiceMethod(string $methodName, $return, ?string $moduleName = null)
    {
        $moduleName = $this->getModuleName($moduleName);
        $className = $this->resolveClassName(static::SERVICE_CLASS_NAME_PATTERN, $moduleName);

        if (!method_exists($className, $methodName)) {
            throw new Exception(sprintf('You tried to mock a not existing method "%s". Available methods are "%s"', $methodName, implode(', ', get_class_methods($className))));
        }

        if (!isset($this->mockedServiceMethods[$moduleName])) {
            $this->mockedServiceMethods[$moduleName] = [];
        }

        $this->mockedServiceMethods[$moduleName][$methodName] = $return;
        /** @var \Spryker\Service\Kernel\AbstractService $facadeStub */
        $facadeStub = Stub::make($className, $this->mockedServiceMethods[$moduleName]);
        $this->serviceStubs[$moduleName] = $facadeStub;

        return $this->serviceStubs[$moduleName];
    }

    /**
     * @param string|null $moduleName
     *
     * @return \Spryker\Service\Kernel\AbstractService
     */
    public function getService(?string $moduleName = null): AbstractService
    {
        $moduleName = $this->getModuleName($moduleName);

        if (!isset($this->serviceStubs[$moduleName])) {
            $this->serviceStubs[$moduleName] = $this->createService($moduleName);
        }

        $this->serviceStubs[$moduleName] = $this->injectFactory($this->serviceStubs[$moduleName], $moduleName);

        return $this->serviceStubs[$moduleName];
    }

    /**
     * @param \Spryker\Service\Kernel\AbstractService $service
     * @param string $moduleName
     *
     * @return \Spryker\Service\Kernel\AbstractService
     */
    protected function injectFactory(AbstractService $service, string $moduleName): AbstractService
    {
        $service->setFactory($this->getFactory($moduleName));

        return $service;
    }

    /**
     * @param string $moduleName
     *
     * @return \Spryker\Service\Kernel\AbstractService
     */
    protected function createService(string $moduleName): AbstractService
    {
        $className = $this->resolveClassName(static::SERVICE_CLASS_NAME_PATTERN, $moduleName);

        return new $className();
    }

    /**
     * @param string $methodName
     * @param mixed $return
     * @param string|null $moduleName
     *
     * @throws \Exception
     *
     * @return object|\Spryker\Service\Kernel\AbstractServiceFactory
     */
    public function mockFactoryMethod(string $methodName, $return, ?string $moduleName = null)
    {
        $moduleName = $this->getModuleName($moduleName);
        $className = $this->resolveClassName(static::SERVICE_FACTORY_CLASS_NAME_PATTERN, $moduleName);

        if (!method_exists($className, $methodName)) {
            throw new Exception(sprintf('You tried to mock a not existing method "%s". Available methods are "%s"', $methodName, implode(', ', get_class_methods($className))));
        }

        if (!isset($this->mockedFactoryMethods[$moduleName])) {
            $this->mockedFactoryMethods[$moduleName] = [];
        }

        $this->mockedFactoryMethods[$moduleName][$methodName] = $return;
        /** @var \Spryker\Service\Kernel\AbstractServiceFactory $factoryStub */
        $factoryStub = Stub::make($className, $this->mockedFactoryMethods[$moduleName]);
        $this->factoryStubs[$moduleName] = $factoryStub;

        return $this->factoryStubs[$moduleName];
    }

    /**
     * @param string|null $moduleName
     *
     * @return \Spryker\Service\Kernel\AbstractServiceFactory
     */
    public function getFactory(?string $moduleName = null): AbstractServiceFactory
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
     * @param string $moduleName
     *
     * @return \Spryker\Service\Kernel\AbstractServiceFactory
     */
    protected function createFactory(string $moduleName): AbstractServiceFactory
    {
        $moduleFactoryClassName = $this->resolveClassName(static::SERVICE_FACTORY_CLASS_NAME_PATTERN, $moduleName);

        return new $moduleFactoryClassName();
    }

    /**
     * @param \Spryker\Service\Kernel\AbstractServiceFactory $factory
     * @param string $moduleName
     *
     * @return \Spryker\Service\Kernel\AbstractServiceFactory
     */
    protected function injectConfig(AbstractServiceFactory $factory, string $moduleName): AbstractServiceFactory
    {
        if (!$this->hasModule('\\' . ConfigHelper::class)) {
            return $factory;
        }

        $config = $this->getConfig($moduleName);

        if ($config !== null) {
            $factory->setConfig($config);
        }

        return $factory;
    }

    /**
     * @param string $moduleName
     *
     * @return \Spryker\Service\Kernel\AbstractBundleConfig|null
     */
    protected function getConfig(string $moduleName): ?AbstractBundleConfig
    {
        try {
            /** @var \Spryker\Service\Kernel\AbstractBundleConfig $moduleConfig */
            $moduleConfig = $this->getConfigHelper()->getModuleConfig($moduleName);

            return $moduleConfig;
        } catch (Throwable $throwable) {
            return null;
        }
    }

    /**
     * @param \Spryker\Service\Kernel\AbstractServiceFactory $factory
     * @param string $moduleName
     *
     * @return \Spryker\Service\Kernel\AbstractServiceFactory
     */
    protected function injectContainer(AbstractServiceFactory $factory, string $moduleName): AbstractServiceFactory
    {
        if ($this->hasModule('\\' . DependencyProviderHelper::class)) {
            $factory->setContainer($this->getContainer($moduleName));
        }

        return $factory;
    }

    /**
     * @param string $moduleName
     *
     * @return \Spryker\Service\Kernel\Container
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

        $this->serviceStubs = [];
        $this->mockedServiceMethods = [];
    }
}
