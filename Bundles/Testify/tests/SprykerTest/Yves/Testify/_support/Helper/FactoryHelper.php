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
     * @return object|\Spryker\Zed\Kernel\Business\AbstractBusinessFactory
     */
    public function mockFactoryMethod(string $methodName, $return)
    {
        $className = $this->getFactoryClassName();

        if (!method_exists($className, $methodName)) {
            throw new Exception(sprintf('You tried to mock a not existing method "%s". Available methods are "%s"', $methodName, implode(', ', get_class_methods($className))));
        }

        $this->mockedFactoryMethods[$methodName] = $return;
        $this->factoryStub = Stub::make($className, $this->mockedFactoryMethods);

        return $this->factoryStub;
    }

    /**
     * @return \Spryker\Yves\Kernel\AbstractFactory
     */
    public function getFactory()
    {
        if ($this->factoryStub !== null) {
            return $this->factoryStub;
        }

        $moduleFactory = $this->createFactory();
        if ($this->hasModule('\\' . ConfigHelper::class)) {
            $moduleFactory->setConfig($this->getConfig());
        }

        return $moduleFactory;
    }

    /**
     * @return \Spryker\Yves\Kernel\AbstractFactory
     */
    protected function createFactory()
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

        return sprintf(static::FACTORY_CLASS_NAME_PATTERN, $namespaceParts[0], $namespaceParts[2]);
    }

    /**
     * @return \Spryker\Yves\Kernel\AbstractBundleConfig
     */
    protected function getConfig()
    {
        return $this->getConfigHelper()->getModuleConfig();
    }

    /**
     * @return \SprykerTest\Shared\Testify\Helper\ConfigHelper
     */
    protected function getConfigHelper(): ConfigHelper
    {
        return $this->getModule('\\' . ConfigHelper::class);
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
    }
}
