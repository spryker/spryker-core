<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Testify\Locator\Business;

use Spryker\Shared\Kernel\BundleConfigMock\BundleConfigMock;
use Spryker\Shared\Kernel\BundleProxy as KernelBundleProxy;
use Spryker\Shared\Kernel\ContainerMocker\ContainerMocker;
use Spryker\Shared\Testify\Config\TestifyConfig;
use Spryker\Zed\Kernel\AbstractFactory;
use Spryker\Zed\Kernel\Business\AbstractFacade;
use Spryker\Zed\Kernel\ClassResolver\Config\BundleConfigResolver;
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
     * @var string
     */
    private $bundle;

    /**
     * @param \Spryker\Zed\Testify\Locator\Business\BusinessLocator $locator
     */
    public function __construct(BusinessLocator $locator)
    {
        $this->locator = $locator;
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
     * @return object
     */
    public function __call($method, $arguments)
    {
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
        /** @var \Spryker\Zed\Kernel\Container $container */
        $container = $configurator->getContainer();
        $container = $dependencyProvider->provideBusinessLayerDependencies(
            $container
        );
        $container = $this->overwriteForTesting($container);

        $bundleConfig = $this->getBundleConfig($factory);

        $factory->setContainer($container);
        $factory->setConfig($bundleConfig);
        $facade->setFactory($factory);

        return $facade;
    }

    /**
     * @return \Spryker\Shared\Testify\Locator\TestifyConfiguratorInterface
     */
    private function getConfigurator()
    {
        $config = new TestifyConfig();
        $container = new Container();
        $container->setLocator($this->locator);

        return new TestifyConfigurator($container, $config);
    }

    /**
     * @param \Spryker\Zed\Kernel\Business\AbstractFacade $facade
     *
     * @return \Spryker\Zed\Kernel\Business\AbstractBusinessFactory
     */
    private function getFactory(AbstractFacade $facade)
    {
        $factoryResolver = new FactoryResolver();

        /** @var \Spryker\Zed\Kernel\Business\AbstractBusinessFactory $factory */
        $factory = $factoryResolver->resolve($facade);

        return $factory;
    }

    /**
     * @param string $method
     * @param array $arguments
     *
     * @return \Spryker\Zed\Kernel\Business\AbstractFacade
     */
    private function getFacade($method, array $arguments)
    {
        /** @var \Spryker\Zed\Kernel\Business\AbstractFacade $facade */
        $facade = parent::__call($method, $arguments);

        return $facade;
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

    /**
     * @param \Spryker\Zed\Kernel\AbstractFactory $factory
     *
     * @return \Spryker\Zed\Kernel\AbstractBundleConfig
     */
    private function getBundleConfig(AbstractFactory $factory)
    {
        $bundleConfigResolver = new BundleConfigResolver();

        $config = $bundleConfigResolver->resolve($factory);
        $bundleConfig = new BundleConfigMock();

        if ($bundleConfig->hasBundleConfigMock($config)) {
            /** @var \Spryker\Zed\Kernel\AbstractBundleConfig $config */
            $config = $bundleConfig->getBundleConfigMock($config);
        }

        return $config;
    }
}
