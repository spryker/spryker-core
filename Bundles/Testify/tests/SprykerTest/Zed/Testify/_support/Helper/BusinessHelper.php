<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Testify\Helper;

use Codeception\Configuration;
use Codeception\Module;
use Codeception\Stub;
use Codeception\TestInterface;
use Exception;
use Spryker\Zed\Kernel\Business\AbstractFacade;
use SprykerTest\Shared\Testify\Helper\ConfigHelper;

class BusinessHelper extends Module
{
    protected const BUSINESS_FACTORY_CLASS_NAME_PATTERN = '\%1$s\%2$s\%3$s\Business\%3$sBusinessFactory';
    protected const BUSINESS_FACADE_CLASS_NAME_PATTERN = '\%1$s\%2$s\%3$s\Business\%3$sFacade';

    /**
     * @var array
     */
    protected $dependencies = [];

    /**
     * @var array
     */
    protected $mockedFacadeMethods = [];

    /**
     * @var \Spryker\Zed\Kernel\Business\AbstractFacade|null
     */
    protected $facadeStub;

    /**
     * @var array
     */
    protected $mockedFactoryMethods = [];

    /**
     * @var \Spryker\Zed\Kernel\Business\AbstractBusinessFactory|null
     */
    protected $factoryStub;

    /**
     * @param string $methodName
     * @param mixed $return
     *
     * @throws \Exception
     *
     * @return object|\Spryker\Zed\Kernel\Business\AbstractFacade
     */
    public function mockFacadeMethod(string $methodName, $return)
    {
        $className = $this->getFacadeClassName();

        if (!method_exists($className, $methodName)) {
            throw new Exception(sprintf('You tried to mock a not existing method "%s". Available methods are "%s"', $methodName, implode(', ', get_class_methods($className))));
        }

        $this->mockedFacadeMethods[$methodName] = $return;

        /** @var \Spryker\Zed\Kernel\Business\AbstractFacade $facadeStub */
        $facadeStub = Stub::make($className, $this->mockedFacadeMethods);
        $this->facadeStub = $facadeStub;

        return $this->facadeStub;
    }

    /**
     * @return \Spryker\Zed\Kernel\Business\AbstractFacade
     */
    public function getFacade()
    {
        $facade = $this->createFacade();
        $facade->setFactory($this->getFactory());

        if ($this->facadeStub !== null) {
            return $this->injectFactory($this->facadeStub);
        }

        $facade = $this->createFacade();

        return $this->injectFactory($facade);
    }

    /**
     * @param \Spryker\Zed\Kernel\Business\AbstractFacade $facade
     *
     * @return \Spryker\Zed\Kernel\Business\AbstractFacade
     */
    protected function injectFactory($facade)
    {
        $facade->setFactory($this->getFactory());

        return $facade;
    }

    /**
     * @return \Spryker\Zed\Kernel\Business\AbstractFacade
     */
    protected function createFacade(): AbstractFacade
    {
        $className = $this->getFacadeClassName();

        return new $className();
    }

    /**
     * @return string
     */
    protected function getFacadeClassName(): string
    {
        $config = Configuration::config();
        $namespaceParts = explode('\\', $config['namespace']);

        return sprintf(static::BUSINESS_FACADE_CLASS_NAME_PATTERN, rtrim($namespaceParts[0], 'Test'), $namespaceParts[1], $namespaceParts[2]);
    }

    /**
     * @param string $methodName
     * @param mixed $return
     *
     * @throws \Exception
     *
     * @return object|\Spryker\Zed\Kernel\Business\AbstractBusinessFactory
     */
    public function mockFactoryMethod(string $methodName, $return)
    {
        $className = $this->getFactoryClassName();

        if (!method_exists($className, $methodName)) {
            throw new Exception(sprintf('You tried to mock a not existing method "%s". Available methods are "%s"', $methodName, implode(', ', get_class_methods($className))));
        }

        $this->mockedFactoryMethods[$methodName] = $return;

        /** @var \Spryker\Zed\Kernel\Business\AbstractBusinessFactory $factoryStub */
        $factoryStub = Stub::make($className, $this->mockedFactoryMethods);

        $this->factoryStub = $factoryStub;

        return $this->factoryStub;
    }

    /**
     * @return \Spryker\Zed\Kernel\Business\AbstractBusinessFactory
     */
    public function getFactory()
    {
        if ($this->factoryStub !== null) {
            return $this->injectConfig($this->factoryStub);
        }

        $moduleFactory = $this->createModuleFactory();

        return $this->injectConfig($moduleFactory);
    }

    /**
     * @return \Spryker\Zed\Kernel\Business\AbstractBusinessFactory
     */
    protected function createModuleFactory()
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

        return sprintf(static::BUSINESS_FACTORY_CLASS_NAME_PATTERN, rtrim($namespaceParts[0], 'Test'), $namespaceParts[1], $namespaceParts[2]);
    }

    /**
     * @param \Spryker\Zed\Kernel\Business\AbstractBusinessFactory $businessFactory
     *
     * @return \Spryker\Zed\Kernel\Business\AbstractBusinessFactory
     */
    protected function injectConfig($businessFactory)
    {
        if ($this->hasModule('\\' . ConfigHelper::class)) {
            $businessFactory->setConfig($this->getConfig());
        }

        return $businessFactory;
    }

    /**
     * @return \Spryker\Zed\Kernel\AbstractBundleConfig
     */
    protected function getConfig()
    {
        /** @var \Spryker\Zed\Kernel\AbstractBundleConfig $config */
        $config = $this->getConfigHelper()->getModuleConfig();

        return $config;
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
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _before(TestInterface $test)
    {
        $this->factoryStub = null;
        $this->mockedFactoryMethods = [];
        $this->facadeStub = null;
        $this->mockedFacadeMethods = [];
    }
}
