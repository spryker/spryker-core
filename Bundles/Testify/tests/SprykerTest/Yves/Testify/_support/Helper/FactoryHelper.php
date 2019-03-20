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
use Spryker\Shared\Testify\Locator\TestifyConfiguratorInterface;
use Spryker\Zed\Testify\Locator\Business\BusinessLocator as Locator;
use SprykerTest\Shared\Testify\Helper\ConfigHelper;

class FactoryHelper extends Module
{
    /**
     * @var array
     */
    protected $config = [
        'projectNamespaces' => [],
        'coreNamespaces' => [
            'Spryker',
        ],
    ];

    /**
     * @var array
     */
    protected $dependencies = [];

    /**
     * @var \Spryker\Zed\Kernel\Business\AbstractBusinessFactory|null
     */
    protected $factoryStub;

    /**
     * @var array
     */
    protected $mockedFactoryMethods = [];

    /**
     * @return \Spryker\Shared\Kernel\LocatorLocatorInterface|\Generated\Yves\Ide\AutoCompletion|\Generated\Service\Ide\AutoCompletion
     */
    public function getLocator()
    {
        return new Locator($this->config['projectNamespaces'], $this->config['coreNamespaces'], $this->createClosure());
    }

    /**
     * @param string $key
     * @param mixed $value
     *
     * @return $this
     */
    public function setDependency($key, $value)
    {
        $this->dependencies[$key] = $value;

        return $this;
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

        return sprintf('\%1$s\Yves\%2$s\%2$sFactory', $namespaceParts[0], $namespaceParts[2]);
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
     * @return \Closure
     */
    private function createClosure()
    {
        $dependencies = $this->getDependencies();
        $callback = function (TestifyConfiguratorInterface $configurator) use ($dependencies) {
            foreach ($dependencies as $key => $value) {
                $configurator->getContainer()->set($key, $value);
            }
        };

        return $callback;
    }

    /**
     * @return array
     */
    private function getDependencies()
    {
        $dependencies = $this->dependencies;
        $this->dependencies = [];

        return $dependencies;
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
