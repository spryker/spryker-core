<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Testify\Helper;

use Codeception\Module;
use Codeception\Stub;
use Codeception\TestInterface;
use Exception;
use Spryker\Client\Kernel\AbstractBundleConfig;
use Spryker\Client\Kernel\AbstractClient;
use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Kernel\Container;
use SprykerTest\Shared\Testify\Helper\ClassResolverTrait;
use SprykerTest\Shared\Testify\Helper\ModuleNameTrait;
use Throwable;

class ClientHelper extends Module
{
    use ClassResolverTrait;
    use ModuleNameTrait;
    use ConfigHelperTrait;
    use DependencyProviderHelperTrait;

    /**
     * @var string
     */
    protected const CLIENT_CLASS_NAME_PATTERN = '\%1$s\Client\%3$s\%3$sClient';

    /**
     * @var string
     */
    protected const CLIENT_FACTORY_CLASS_NAME_PATTERN = '\%1$s\Client\%3$s\%3$sFactory';

    /**
     * @var array<\Spryker\Client\Kernel\AbstractClient>
     */
    protected $clientStubs = [];

    /**
     * @var array
     */
    protected $mockedClientMethods = [];

    /**
     * @var array<\Spryker\Client\Kernel\AbstractFactory>
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
     * @return \Spryker\Client\Kernel\AbstractClient
     */
    public function mockClientMethod(string $methodName, $return, ?string $moduleName = null)
    {
        $moduleName = $this->getModuleName($moduleName);
        $className = $this->resolveClassName(static::CLIENT_CLASS_NAME_PATTERN, $moduleName);

        if (!method_exists($className, $methodName)) {
            throw new Exception(sprintf('You tried to mock a not existing method "%s". Available methods are "%s"', $methodName, implode(', ', get_class_methods($className))));
        }

        if (!isset($this->mockedClientMethods[$moduleName])) {
            $this->mockedClientMethods[$moduleName] = [];
        }

        $this->mockedClientMethods[$moduleName][$methodName] = $return;
        /** @var \Spryker\Client\Kernel\AbstractClient $clientStub */
        $clientStub = Stub::make($className, $this->mockedClientMethods[$moduleName]);
        $this->clientStubs[$moduleName] = $clientStub;

        return $this->clientStubs[$moduleName];
    }

    /**
     * @param string|null $moduleName
     *
     * @return \Spryker\Client\Kernel\AbstractClient|null
     */
    public function getClient(?string $moduleName = null)
    {
        $moduleName = $this->getModuleName($moduleName);

        if (!isset($this->clientStubs[$moduleName])) {
            $this->clientStubs[$moduleName] = $this->createClient($moduleName);
        }

        $this->clientStubs[$moduleName] = $this->injectFactory($this->clientStubs[$moduleName], $moduleName);

        return $this->clientStubs[$moduleName];
    }

    /**
     * @param string|null $moduleName
     *
     * @return \Spryker\Client\Kernel\AbstractClient
     */
    protected function createClient(?string $moduleName = null)
    {
        $moduleName = $this->getModuleName($moduleName);
        $moduleClientClassName = $this->resolveClassName(static::CLIENT_CLASS_NAME_PATTERN, $moduleName);

        return new $moduleClientClassName();
    }

    /**
     * @param \Spryker\Client\Kernel\AbstractClient $client
     * @param string $moduleName
     *
     * @return \Spryker\Client\Kernel\AbstractClient
     */
    protected function injectFactory(AbstractClient $client, string $moduleName): AbstractClient
    {
        $client->setFactory($this->getFactory($moduleName));

        return $client;
    }

    /**
     * @param string $methodName
     * @param mixed $return
     * @param string|null $moduleName
     *
     * @throws \Exception
     *
     * @return \Spryker\Client\Kernel\AbstractFactory|object
     */
    public function mockFactoryMethod(string $methodName, $return, ?string $moduleName = null)
    {
        $moduleName = $this->getModuleName($moduleName);
        $className = $this->resolveClassName(static::CLIENT_FACTORY_CLASS_NAME_PATTERN, $moduleName);

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
     * @param string $moduleName
     *
     * @return \Spryker\Client\Kernel\AbstractFactory
     */
    protected function createFactory(string $moduleName): AbstractFactory
    {
        $moduleFactoryClassName = $this->resolveClassName(static::CLIENT_FACTORY_CLASS_NAME_PATTERN, $moduleName);

        return new $moduleFactoryClassName();
    }

    /**
     * @param \Spryker\Client\Kernel\AbstractFactory $clientFactory
     * @param string $moduleName
     *
     * @return \Spryker\Client\Kernel\AbstractFactory
     */
    protected function injectConfig(AbstractFactory $clientFactory, string $moduleName): AbstractFactory
    {
        if (!$this->hasModule('\\' . ConfigHelper::class)) {
            return $clientFactory;
        }

        $config = $this->getConfig($moduleName);

        if ($config !== null) {
            $clientFactory->setConfig($config);
        }

        return $clientFactory;
    }

    /**
     * @param string $moduleName
     *
     * @return \Spryker\Client\Kernel\AbstractBundleConfig|null
     */
    protected function getConfig(string $moduleName): ?AbstractBundleConfig
    {
        try {
            /** @var \Spryker\Client\Kernel\AbstractBundleConfig $moduleConfig */
            $moduleConfig = $this->getConfigHelper()->getModuleConfig($moduleName);

            return $moduleConfig;
        } catch (Throwable $throwable) {
            return null;
        }
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
    public function _before(TestInterface $test)
    {
        $this->clientStubs = [];
        $this->mockedClientMethods = [];
        $this->factoryStubs = [];
        $this->mockedFactoryMethods = [];
    }
}
