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
use Spryker\Yves\Kernel\Container;

class DependencyProviderHelper extends Module
{
    protected const DEPENDENCY_PROVIDER_CLASS_NAME_PATTERN = '\%1$s\Yves\%2$s\%2$sDependencyProvider';

    /**
     * @var \Spryker\Yves\Kernel\AbstractBundleDependencyProvider|null
     */
    protected $dependencyProviderStub;

    /**
     * @var array
     */
    protected $mockedDependencyProviderMethods = [];

    /**
     * @param string $methodName
     * @param mixed $return
     *
     * @throws \Exception
     *
     * @return object|\Spryker\Yves\Kernel\AbstractBundleDependencyProvider
     */
    public function mockDependencyProviderMethod(string $methodName, $return)
    {
        $className = $this->getDependencyProviderClassName();

        if (!method_exists($className, $methodName)) {
            throw new Exception(sprintf('You tried to mock a not existing method "%s". Available methods are "%s"', $methodName, implode(', ', get_class_methods($className))));
        }

        $this->mockedDependencyProviderMethods[$methodName] = $return;
        $this->dependencyProviderStub = Stub::make($className, $this->mockedDependencyProviderMethods);

        return $this->dependencyProviderStub;
    }

    /**
     * @return \Spryker\Yves\Kernel\Container
     */
    public function getContainer()
    {
        $container = new Container();
        if ($this->dependencyProviderStub !== null) {
            return $this->dependencyProviderStub->provideDependencies($container);
        }

        $dependencyProvider = $this->createDependencyProvider();

        return $dependencyProvider->provideDependencies($container);
    }

    /**
     * @return \Spryker\Yves\Kernel\AbstractBundleDependencyProvider
     */
    protected function createDependencyProvider()
    {
        $dependencyProviderClassName = $this->getDependencyProviderClassName();

        return new $dependencyProviderClassName();
    }

    /**
     * @return string
     */
    protected function getDependencyProviderClassName(): string
    {
        $config = Configuration::config();
        $namespaceParts = explode('\\', $config['namespace']);

        return sprintf(static::DEPENDENCY_PROVIDER_CLASS_NAME_PATTERN, rtrim($namespaceParts[0], 'Test'), $namespaceParts[2]);
    }

    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _before(TestInterface $test)
    {
        $this->dependencyProviderStub = null;
        $this->mockedDependencyProviderMethods = [];
    }
}
