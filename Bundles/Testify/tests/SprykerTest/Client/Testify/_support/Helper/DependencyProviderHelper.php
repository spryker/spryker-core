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
use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Shared\Kernel\ContainerMocker\ContainerGlobals;
use Spryker\Shared\Kernel\ContainerMocker\ContainerMocker;
use SprykerTest\Shared\Testify\Helper\ClassResolverTrait;

class DependencyProviderHelper extends Module
{
    use ContainerMocker;
    use ClassResolverTrait;

    protected const DEPENDENCY_PROVIDER_CLASS_NAME_PATTERN = '\%1$s\Client\%3$s\%3$sDependencyProvider';
    protected const MODULE_NAME_POSITION = 2;

    /**
     * @var \Spryker\Client\Kernel\AbstractDependencyProvider|null
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
     * @return \Spryker\Client\Kernel\AbstractDependencyProvider
     */
    public function mockDependencyProviderMethod(string $methodName, $return, ?string $moduleName = null): AbstractDependencyProvider
    {
        $moduleName = $this->getModuleName($moduleName);
        $className = $this->resolveClassName(static::DEPENDENCY_PROVIDER_CLASS_NAME_PATTERN, $moduleName);

        if (!method_exists($className, $methodName)) {
            throw new Exception(sprintf('You tried to mock a not existing method "%s". Available methods are "%s"', $methodName, implode(', ', get_class_methods($className))));
        }

        $this->mockedDependencyProviderMethods[$methodName] = $return;

        /** @var \Spryker\Client\Kernel\AbstractDependencyProvider $dependencyProvider */
        $dependencyProvider = Stub::make($className, $this->mockedDependencyProviderMethods);
        $this->dependencyProviderStub = $dependencyProvider;

        return $this->dependencyProviderStub;
    }

    /**
     * @param string|null $moduleName
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function getModuleContainer(?string $moduleName = null): Container
    {
        $container = new Container();
        if ($this->dependencyProviderStub !== null) {
            return $this->dependencyProviderStub->provideServiceLayerDependencies($container);
        }

        $moduleName = $this->getModuleName($moduleName);
        $dependencyProvider = $this->createDependencyProvider($moduleName);
        $container = $dependencyProvider->provideServiceLayerDependencies($container);

        /** @var \Spryker\Client\Kernel\Container $container */
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
     * @return \Spryker\Client\Kernel\AbstractDependencyProvider
     */
    protected function createDependencyProvider(string $moduleName): AbstractDependencyProvider
    {
        $dependencyProviderClassName = $this->resolveClassName(static::DEPENDENCY_PROVIDER_CLASS_NAME_PATTERN, $moduleName);

        return new $dependencyProviderClassName();
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
