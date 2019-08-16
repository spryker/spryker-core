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
use Spryker\Client\Kernel\AbstractClient;
use SprykerTest\Shared\Testify\Helper\ConfigHelper;

class ClientHelper extends Module
{
    protected const CLIENT_FACTORY_CLASS_NAME_PATTERN = '\%1$s\%2$s\%3$s\%3$sFactory';
    protected const CLIENT_CLASS_NAME_PATTERN = '\%1$s\%2$s\%3$s\%3$sClient';

    /**
     * @var \Spryker\Client\Kernel\AbstractFactory|null
     */
    protected $factoryStub;

    /**
     * @var array
     */
    protected $mockedFactoryMethods = [];

    /**
     * @return \Spryker\Client\Kernel\AbstractClient
     */
    public function getClient()
    {
        $client = $this->createClient();
        $client->setFactory($this->getFactory());

        return $client;
    }

    /**
     * @return \Spryker\Client\Kernel\AbstractClient
     */
    protected function createClient(): AbstractClient
    {
        $clientClassName = $this->getClientClassName();

        return new $clientClassName();
    }

    /**
     * @param string $methodName
     * @param mixed $return
     *
     * @throws \Exception
     *
     * @return object|\Spryker\Client\Kernel\AbstractFactory
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
     * @return \Spryker\Client\Kernel\AbstractFactory
     */
    public function getFactory()
    {
        if ($this->factoryStub !== null) {
            return $this->injectConfig($this->factoryStub);
        }

        $moduleFactory = $this->createClientFactory();

        return $this->injectConfig($moduleFactory);
    }

    /**
     * @return \Spryker\Client\Kernel\AbstractFactory
     */
    protected function createClientFactory()
    {
        $moduleFactoryClassName = $this->getFactoryClassName();

        return new $moduleFactoryClassName();
    }

    /**
     * @return string
     */
    protected function getClientClassName(): string
    {
        $config = Configuration::config();
        $namespaceParts = explode('\\', $config['namespace']);

        return sprintf(static::CLIENT_CLASS_NAME_PATTERN, rtrim($namespaceParts[0], 'Test'), $namespaceParts[1], $namespaceParts[2]);
    }

    /**
     * @return string
     */
    protected function getFactoryClassName(): string
    {
        $config = Configuration::config();
        $namespaceParts = explode('\\', $config['namespace']);

        return sprintf(static::CLIENT_FACTORY_CLASS_NAME_PATTERN, rtrim($namespaceParts[0], 'Test'), $namespaceParts[1], $namespaceParts[2]);
    }

    /**
     * @param \Spryker\Client\Kernel\AbstractFactory|object $clientFactory
     *
     * @return \Spryker\Client\Kernel\AbstractFactory
     */
    protected function injectConfig($clientFactory)
    {
        if ($this->hasModule('\\' . ConfigHelper::class)) {
            $clientFactory->setConfig($this->getConfig());
        }

        return $clientFactory;
    }

    /**
     * @return \Spryker\Client\Kernel\AbstractBundleConfig
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
