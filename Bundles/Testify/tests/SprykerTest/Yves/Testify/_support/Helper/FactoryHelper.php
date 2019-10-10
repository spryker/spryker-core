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
use Spryker\Yves\Kernel\AbstractBundleConfig;
use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\Kernel\Container;
use SprykerTest\Shared\Testify\Helper\ConfigHelper;

class FactoryHelper extends Module
{
    protected const FACTORY_CLASS_NAME_PATTERN = '\%1$s\Yves\%2$s\%2$sFactory';

    /**
     * @var \Spryker\Yves\Kernel\AbstractFactory|null
     */
    protected $factoryStub;

    /**
     * @var array
     */
    protected $mockedFactoryMethods = [];

    /**
     * @param string $methodName
     * @param mixed $return
     *
     * @throws \Exception
     *
     * @return \Spryker\Yves\Kernel\AbstractFactory
     */
    public function mockFactoryMethod(string $methodName, $return)
    {
        $className = $this->getFactoryClassName();

        if (!method_exists($className, $methodName)) {
            throw new Exception(sprintf('You tried to mock a not existing method "%s". Available methods are "%s"', $methodName, implode(', ', get_class_methods($className))));
        }

        $this->mockedFactoryMethods[$methodName] = $return;
        /** @var \Spryker\Yves\Kernel\AbstractFactory $factoryStub */
        $factoryStub = Stub::make($className, $this->mockedFactoryMethods);
        $this->factoryStub = $factoryStub;

        return $this->factoryStub;
    }

    /**
     * @return \Spryker\Yves\Kernel\AbstractFactory
     */
    public function getFactory(): AbstractFactory
    {
        if ($this->factoryStub !== null) {
            $this->factoryStub = $this->injectConfig($this->factoryStub);
            $this->factoryStub = $this->injectContainer($this->factoryStub);

            return $this->factoryStub;
        }

        $moduleFactory = $this->createFactory();

        $moduleFactory = $this->injectConfig($moduleFactory);
        $moduleFactory = $this->injectContainer($moduleFactory);

        return $moduleFactory;
    }

    /**
     * @return \Spryker\Yves\Kernel\AbstractFactory
     */
    protected function createFactory(): AbstractFactory
    {
        $moduleFactoryClassName = $this->getFactoryClassName();

        return new $moduleFactoryClassName();
    }

    /**
     * @return string
     */
    protected function getFactoryClassName(): string
    {
        $config = Configuration::config();
        $namespaceParts = explode('\\', $config['namespace']);

        return sprintf(static::FACTORY_CLASS_NAME_PATTERN, rtrim($namespaceParts[0], 'Test'), $namespaceParts[2]);
    }

    /**
     * @param \Spryker\Yves\Kernel\AbstractFactory $factory
     *
     * @return \Spryker\Yves\Kernel\AbstractFactory
     */
    protected function injectConfig(AbstractFactory $factory): AbstractFactory
    {
        if ($this->hasModule('\\' . ConfigHelper::class)) {
            $factory->setConfig($this->getConfig());
        }

        return $factory;
    }

    /**
     * @return \Spryker\Yves\Kernel\AbstractBundleConfig
     */
    protected function getConfig(): AbstractBundleConfig
    {
        /** @var \Spryker\Yves\Kernel\AbstractBundleConfig $moduleConfig */
        $moduleConfig = $this->getConfigHelper()->getModuleConfig();

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
    protected function injectContainer(AbstractFactory $factory): AbstractFactory
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
        $this->factoryStub = null;
        $this->mockedFactoryMethods = [];
    }
}
