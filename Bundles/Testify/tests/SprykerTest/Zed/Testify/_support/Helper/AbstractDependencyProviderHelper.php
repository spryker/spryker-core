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
use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\Kernel\ContainerMocker\ContainerGlobals;
use Spryker\Shared\Kernel\ContainerMocker\ContainerMocker;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use SprykerTest\Service\Container\Helper\ContainerHelperTrait;
use SprykerTest\Shared\Testify\Helper\ModuleNameTrait;

abstract class AbstractDependencyProviderHelper extends Module
{
    use ModuleNameTrait;
    use ContainerMocker;
    use ContainerHelperTrait;

    protected const DEPENDENCY_PROVIDER_CLASS_NAME_PATTERN = '\%1$s\Zed\%2$s\%2$sDependencyProvider';

    protected const NON_STANDARD_NAMESPACE_PREFIXES = [
        'SprykerShopTest',
        'SprykerSdkTest',
    ];

    /**
     * @var \Spryker\Zed\Kernel\AbstractBundleDependencyProvider|null
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
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function getContainer(): ContainerInterface
    {
        return $this->getContainerHelper()->getContainer();
    }

    /**
     * @param string|null $moduleName
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function getModuleContainer(?string $moduleName = null): Container
    {
        $container = new Container();
        if ($this->dependencyProviderStub !== null) {
            return $this->dependencyProviderStub->provideCommunicationLayerDependencies($container);
        }

        $moduleName = $this->getModuleName($moduleName);
        $dependencyProvider = $this->createDependencyProvider($moduleName);
        $container = $this->provide($dependencyProvider, $container);

        /** @var \Spryker\Zed\Kernel\Container $container */
        $container = $this->overwriteForTesting($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\AbstractBundleDependencyProvider $dependencyProvider
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    abstract protected function provide(AbstractBundleDependencyProvider $dependencyProvider, Container $container): Container;

    /**
     * @param string $methodName
     * @param mixed $return
     * @param string|null $moduleName
     *
     * @throws \Exception
     *
     * @return \Spryker\Zed\Kernel\AbstractBundleDependencyProvider
     */
    public function mockDependencyProviderMethod(string $methodName, $return, ?string $moduleName = null): AbstractBundleDependencyProvider
    {
        $moduleName = $this->getModuleName($moduleName);
        $className = $this->getDependencyProviderClassName($moduleName);

        if (!method_exists($className, $methodName)) {
            throw new Exception(sprintf('You tried to mock a not existing method "%s". Available methods are "%s"', $methodName, implode(', ', get_class_methods($className))));
        }

        $this->mockedDependencyProviderMethods[$methodName] = $return;

        /** @var \Spryker\Zed\Kernel\AbstractBundleDependencyProvider $dependencyProvider */
        $dependencyProvider = Stub::make($className, $this->mockedDependencyProviderMethods);
        $this->dependencyProviderStub = $dependencyProvider;

        return $this->dependencyProviderStub;
    }

    /**
     * @param string $moduleName
     *
     * @return \Spryker\Zed\Kernel\AbstractBundleDependencyProvider
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
        $namespacePrefix = $namespaceParts[0];

        $classNameCandidate = sprintf(static::DEPENDENCY_PROVIDER_CLASS_NAME_PATTERN, 'Spryker', $moduleName);

        if (in_array($namespacePrefix, static::NON_STANDARD_NAMESPACE_PREFIXES, true) && class_exists($classNameCandidate)) {
            return $classNameCandidate;
        }

        return sprintf(static::DEPENDENCY_PROVIDER_CLASS_NAME_PATTERN, rtrim($namespacePrefix, 'Test'), $moduleName);
    }

    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _after(TestInterface $test): void
    {
        $this->dependencyProviderStub = null;
        $this->mockedDependencyProviderMethods = [];
        $this->containerGlobals->reset();
    }
}
