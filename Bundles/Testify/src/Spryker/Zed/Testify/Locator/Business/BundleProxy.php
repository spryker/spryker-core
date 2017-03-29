<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Testify\Locator\Business;

use Spryker\Shared\Kernel\BundleProxy as KernelBundleProxy;
use Spryker\Shared\Kernel\ContainerMocker\ContainerMocker;
use Spryker\Shared\Kernel\KernelConstants;
use Spryker\Shared\Testify\Config\TestifyConfig;
use Spryker\Zed\Kernel\AbstractFactory;
use Spryker\Zed\Kernel\Business\AbstractFacade;
use Spryker\Zed\Kernel\ClassResolver\DependencyProvider\DependencyProviderResolver;
use Spryker\Zed\Kernel\ClassResolver\Factory\FactoryResolver;
use Spryker\Zed\Testify\Locator\TestifyConfigurator;

class BundleProxy extends KernelBundleProxy
{

    use ContainerMocker;

    /**
     * @var \Spryker\Zed\Testify\Locator\Business\BusinessLocator
     */
    private $locator;

    /**
     * @var \Spryker\Shared\Testify\Locator\TestifyConfiguratorInterface
     */
    private static $configurator;

    /**
     * @var string
     */
    private $bundle;

    /**
     * @var array
     */
    private $projectNamespaces = [];

    /**
     * @var array
     */
    private $coreNamespaces = [];

    /**
     * @param \Spryker\Zed\Testify\Locator\Business\BusinessLocator $locator
     */
    public function __construct(BusinessLocator $locator)
    {
        $this->locator = $locator;
    }

    /**
     * @param array $coreNamespaces
     *
     * @return $this
     */
    public function setCoreNamespaces($coreNamespaces)
    {
        $this->coreNamespaces = $coreNamespaces;

        return $this;
    }

    /**
     * @param array $projectNamespaces
     *
     * @return $this
     */
    public function setProjectNamespaces($projectNamespaces)
    {
        $this->projectNamespaces = $projectNamespaces;

        return $this;
    }

    /**
     * @param string $bundle
     *
     * @return $this
     */
    public function setBundle($bundle)
    {
        parent::setBundle($bundle);

        $this->bundle = ucfirst($bundle);

        return $this;
    }

    /**
     * @param string $method
     * @param array $arguments
     *
     * @return \Spryker\Zed\Kernel\Business\AbstractFacade|\Spryker\Zed\Kernel\Persistence\AbstractQueryContainer|\Spryker\Service\Kernel\AbstractService|object
     */
    public function __call($method, $arguments)
    {
        $this->configureNamespacesForClassResolver();

        if ($method === 'facade') {
            return $this->createFacade($method, $arguments);
        }

        return parent::__call($method, $arguments);
    }

    /**
     * @param string $method
     * @param array $arguments
     *
     * @return \Spryker\Zed\Kernel\Business\AbstractFacade
     */
    private function createFacade($method, array $arguments)
    {
        $facade = $this->getFacade($method, $arguments);
        $factory = $this->getFactory($facade);
        $dependencyProvider = $this->getDependencyProvider($factory);

        $configurator = $this->getConfigurator();
        $container = $dependencyProvider->provideBusinessLayerDependencies(
            $configurator->getContainer()
        );
        $container = $this->overwriteForTesting($container);

        $factory->setContainer($container);

        $facade->setFactory($factory);

        return $facade;
    }

    /**
     * @return \Spryker\Shared\Testify\Locator\TestifyConfiguratorInterface
     */
    private function getConfigurator()
    {
        if (!static::$configurator) {
            $config = new TestifyConfig();
            $container = new Container();
            $container->setLocator($this->locator);

            static::$configurator = new TestifyConfigurator($container, $config);
        }

        return static::$configurator;
    }

    /**
     * @return void
     */
    private function configureNamespacesForClassResolver()
    {
        $configurator = $this->getConfigurator();
        $configurator->getConfig()->set(KernelConstants::PROJECT_NAMESPACES, $this->projectNamespaces);
        $configurator->getConfig()->set(KernelConstants::CORE_NAMESPACES, $this->coreNamespaces);
    }

    /**
     * @param \Spryker\Zed\Kernel\Business\AbstractFacade $facade
     *
     * @return \Spryker\Zed\Kernel\AbstractFactory|\Spryker\Zed\Kernel\Business\AbstractBusinessFactory
     */
    private function getFactory(AbstractFacade $facade)
    {
        $factoryResolver = new FactoryResolver();

        return $factoryResolver->resolve($facade);
    }

    /**
     * @param string $method
     * @param array $arguments
     *
     * @return \Spryker\Zed\Kernel\Business\AbstractFacade|object
     */
    private function getFacade($method, array $arguments)
    {
        return parent::__call($method, $arguments);
    }

    /**
     * @param \Spryker\Zed\Kernel\AbstractFactory $factory
     *
     * @return \Spryker\Zed\Kernel\AbstractBundleDependencyProvider
     */
    private function getDependencyProvider(AbstractFactory $factory)
    {
        $dependencyResolver = new DependencyProviderResolver();

        return $dependencyResolver->resolve($factory);
    }

}
