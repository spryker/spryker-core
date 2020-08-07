<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Testify\Helper\Communication;

use Codeception\Configuration;
use Codeception\Module;
use Codeception\Stub;
use Codeception\TestInterface;
use Exception;
use Spryker\Zed\Kernel\AbstractBundleConfig;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Kernel\Container;
use SprykerTest\Shared\Testify\ClassResolver\ClassResolverTrait;
use SprykerTest\Shared\Testify\Helper\ConfigHelper;
use SprykerTest\Shared\Testify\Helper\ConfigHelperTrait;
use SprykerTest\Shared\Testify\Helper\ModuleNameTrait;
use Throwable;

class CommunicationHelper extends Module
{
    use ClassResolverTrait;
    use ModuleNameTrait;
    use ConfigHelperTrait;
    use DependencyProviderHelperTrait;

    protected const COMMUNICATION_FACTORY_CLASS_NAME_PATTERN = '\%1$s\Zed\%3$s\Communication\%3$sCommunicationFactory';

    /**
     * @var \Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory[]
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
     * @return \Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory
     */
    public function mockFactoryMethod(string $methodName, $return, ?string $moduleName = null): AbstractCommunicationFactory
    {
        $moduleName = $this->getModuleName($moduleName);
        $className = $this->resolveClassName(static::COMMUNICATION_FACTORY_CLASS_NAME_PATTERN, $moduleName);

        if (!method_exists($className, $methodName)) {
            throw new Exception(sprintf('You tried to mock a not existing method "%s". Available methods are "%s"', $methodName, implode(', ', get_class_methods($className))));
        }

        if (!isset($this->mockedFactoryMethods[$moduleName])) {
            $this->mockedFactoryMethods[$moduleName] = [];
        }

        $this->mockedFactoryMethods[$moduleName][$methodName] = $return;
        /** @var \Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory $factoryStub */
        $factoryStub = Stub::make($className, $this->mockedFactoryMethods[$moduleName]);
        $this->factoryStubs[$moduleName] = $factoryStub;

        return $this->factoryStubs[$moduleName];
    }

    /**
     * @param string|null $moduleName
     *
     * @return \Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory
     */
    public function getFactory(?string $moduleName = null): AbstractCommunicationFactory
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
     * @return \Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory
     */
    protected function createFactory(?string $moduleName = null): AbstractCommunicationFactory
    {
        /** @var AbstractCommunicationFactory $factory */
        $factory = $this->resolveClass(static::COMMUNICATION_FACTORY_CLASS_NAME_PATTERN, $moduleName);

        return $factory;
    }

    /**
     * @param \Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory $moduleFactory
     * @param string $moduleName
     *
     * @return \Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory
     */
    protected function injectConfig($moduleFactory, string $moduleName): AbstractCommunicationFactory
    {
        if ($this->hasModule('\\' . ConfigHelper::class)) {
            $config = $this->getConfig($moduleName);

            if ($config === null) {
                return $moduleFactory;
            }

            $moduleFactory->setConfig($this->getConfig($moduleName));
        }

        return $moduleFactory;
    }

    /**
     * @param string $moduleName
     *
     * @return \Spryker\Zed\Kernel\AbstractBundleConfig|null
     */
    protected function getConfig(string $moduleName): ?AbstractBundleConfig
    {
        try {
            /** @var \Spryker\Zed\Kernel\AbstractBundleConfig $moduleConfig */
            $moduleConfig = $this->getConfigHelper()->getModuleConfig($moduleName);

            return $moduleConfig;
        } catch (Throwable $throwable) {
            return null;
        }
    }

    /**
     * @param \Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory $factory
     * @param string $moduleName
     *
     * @return \Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory
     */
    protected function injectContainer(AbstractCommunicationFactory $factory, string $moduleName): AbstractCommunicationFactory
    {
        if ($this->hasModule('\\' . DependencyProviderHelper::class)) {
            $factory->setContainer($this->getContainer($moduleName));
        }

        return $factory;
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
    }
}
