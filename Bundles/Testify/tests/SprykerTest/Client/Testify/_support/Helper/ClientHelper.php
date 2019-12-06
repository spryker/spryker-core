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

class ClientHelper extends Module
{
    protected const CLIENT_CLASS_NAME_PATTERN = '\%1$s\Client\%2$s\%2$sClient';
    protected const MODULE_NAME_POSITION = 2;

    /**
     * @var \Spryker\Client\Kernel\AbstractClient[]
     */
    protected $clientStubs = [];

    /**
     * @var array
     */
    protected $mockedClientMethods = [];

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
        $className = $this->getClassName($moduleName);

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

        if (isset($this->clientStubs[$moduleName])) {
            return $this->clientStubs[$moduleName];
        }

        $client = $this->createClient($moduleName);

        return $client;
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
     * @return \Spryker\Client\Kernel\AbstractClient
     */
    protected function createClient(?string $moduleName = null)
    {
        $moduleName = $this->getModuleName($moduleName);
        $moduleClientClassName = $this->getClassName($moduleName);

        return new $moduleClientClassName();
    }

    /**
     * @param string $moduleName
     *
     * @return string
     */
    protected function getClassName(string $moduleName): string
    {
        $config = Configuration::config();
        $namespaceParts = explode('\\', $config['namespace']);

        $classNameCandidate = sprintf(static::CLIENT_CLASS_NAME_PATTERN, 'Spryker', $moduleName);

        if ($namespaceParts[0] === 'SprykerShopTest' && class_exists($classNameCandidate)) {
            return $classNameCandidate;
        }

        return sprintf(static::CLIENT_CLASS_NAME_PATTERN, rtrim($namespaceParts[0], 'Test'), $moduleName);
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
    }
}
