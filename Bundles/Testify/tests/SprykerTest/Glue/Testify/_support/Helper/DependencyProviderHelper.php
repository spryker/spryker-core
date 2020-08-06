<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\Testify\Helper;

use Codeception\Configuration;
use Codeception\Module;
use Codeception\Stub;
use Codeception\TestInterface;
use Exception;
use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;
use Spryker\Shared\Kernel\ContainerMocker\ContainerGlobals;
use Spryker\Shared\Kernel\ContainerMocker\ContainerMocker;

class DependencyProviderHelper extends Module
{
    use ContainerMocker;

    protected const DEPENDENCY_PROVIDER_CLASS_NAME_PATTERN = '\%1$s\Glue\%2$s\%2$sDependencyProvider';
    protected const MODULE_NAME_POSITION = 2;

    /**
     * @var \Spryker\Glue\Kernel\AbstractBundleDependencyProvider|null
     */
    protected $dependencyProviderStub;

    /**
     * @var array
     */
    protected $mockedDependencyProviderMethods = [];

    /**
     * @var \Spryker\Shared\Kernel\ContainerMocker\ContainerGlobals
     */
    protected $containerGlobals;

    /**
     * @return void
     */
    public function _initialize()
    {
        $this->containerGlobals = new ContainerGlobals();
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param string|null $onlyFor
     *
     * @return void
     */
    public function setDependency($key, $value, $onlyFor = null)
    {
        $this->containerGlobals->set($key, $value, $onlyFor);
    }

    /**
     * @param string $methodName
     * @param mixed $return
     * @param string|null $moduleName
     *
     * @throws \Exception
     *
     * @return \Spryker\Glue\Kernel\AbstractBundleDependencyProvider
     */
    public function mockDependencyProviderMethod(string $methodName, $return, ?string $moduleName = null): AbstractBundleDependencyProvider
    {
        $moduleName = $this->getModuleName($moduleName);
        $className = $this->getDependencyProviderClassName($moduleName);

        if (!method_exists($className, $methodName)) {
            throw new Exception(sprintf('You tried to mock a not existing method "%s". Available methods are "%s"', $methodName, implode(', ', get_class_methods($className))));
        }

        $this->mockedDependencyProviderMethods[$methodName] = $return;

        /** @var \Spryker\Glue\Kernel\AbstractBundleDependencyProvider $dependencyProvider */
        $dependencyProvider = Stub::make($className, $this->mockedDependencyProviderMethods);
        $this->dependencyProviderStub = $dependencyProvider;

        return $this->dependencyProviderStub;
    }

    /**
     * @param string|null $moduleName
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function getModuleContainer(?string $moduleName = null): Container
    {
        $container = new Container();
        if ($this->dependencyProviderStub !== null) {
            return $this->dependencyProviderStub->provideDependencies($container);
        }

        $moduleName = $this->getModuleName($moduleName);
        $dependencyProvider = $this->createDependencyProvider($moduleName);
        $container = $dependencyProvider->provideDependencies($container);

        /** @var \Spryker\Glue\Kernel\Container $container */
        $container = $this->overwriteForTesting($container);

        return $container;
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
     * @param string $moduleName
     *
     * @return \Spryker\Glue\Kernel\AbstractBundleDependencyProvider
     */
    protected function createDependencyProvider(string $moduleName): AbstractBundleDependencyProvider
    {
        $dependencyProviderClassName = $this->getDependencyProviderClassName($moduleName);

        return new $dependencyProviderClassName();
    }

    /**
     * @param string $moduleName
     *
     * @return string
     */
    protected function getDependencyProviderClassName(string $moduleName): string
    {
        $config = Configuration::config();
        $namespaceParts = explode('\\', $config['namespace']);

        return sprintf(static::DEPENDENCY_PROVIDER_CLASS_NAME_PATTERN, rtrim($namespaceParts[0], 'Test'), $moduleName);
    }

    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _before(TestInterface $test): void
    {
        $this->dependencyProviderStub = null;
        $this->mockedDependencyProviderMethods = [];
        $this->containerGlobals->reset();
    }
}
