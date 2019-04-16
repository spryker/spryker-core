<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Testify\Bootstrap;

use ReflectionClass;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\HttpFragmentServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Silex\ServiceProviderInterface;
use Spryker\Service\Container\Container;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\Application\Application;
use Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface;
use Spryker\Shared\Kernel\Communication\Application as LegacyApplication;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Kernel\Communication\Plugin\Pimple;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class ZedBootstrap
{
    /**
     * @var \Silex\ServiceProviderInterface[]
     */
    private $additionalServiceProvider;

    /**
     * @var \Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface[]
     */
    private $additionalApplicationPlugins;

    /**
     * @var \Spryker\Shared\Kernel\Communication\Application|null
     */
    private $legacyApplication;

    /**
     * @param \Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface[] $additionalApplicationPlugins
     * @param \Silex\ServiceProviderInterface[] $additionalServiceProvider
     */
    public function __construct(array $additionalApplicationPlugins, array $additionalServiceProvider)
    {
        $this->additionalApplicationPlugins = $additionalApplicationPlugins;
        $this->additionalServiceProvider = $additionalServiceProvider;
    }

    /**
     * @return \Spryker\Shared\Application\Application
     */
    public function boot()
    {
        return $this->getApplication();
    }

    /**
     * @return \Spryker\Shared\Application\Application
     */
    private function getApplication(): Application
    {
        $container = $this->getContainer();
        $container = $this->cleanupStatics($container);

        $application = new Application($container);
        $application = $this->registerApplicationPlugins($application);

        if ($this->legacyApplication) {
            $this->legacyApplication->boot();
            Pimple::setApplication($this->legacyApplication);
        }

        $application->boot();

        return $application;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function cleanupStatics(ContainerInterface $container): ContainerInterface
    {
        $reflectionClass = (new ReflectionClass($container));

        foreach (['globalServices', 'globalServiceIdentifier', 'globalServiceIdentifier', 'aliases'] as $property) {
            $reflectionProperty = $reflectionClass->getProperty($property);
            $reflectionProperty->setAccessible(true);
            $reflectionProperty->setValue([]);
        }

        return $container;
    }

    /**
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function getContainer(): ContainerInterface
    {
        if (class_exists(LegacyApplication::class)) {
            $container = new LegacyApplication();
            $container = $this->registerServiceProvider($container);
            $container->set('debug', true);
            $container->set('session.test', true);
            $container->set('locale', $this->getCurrentLocale());
            $container->remove('exception_handler');

            $this->legacyApplication = $container;

            return $container;
        }

        return new Container();
    }

    /**
     * @deprecated Please switch to `Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface`.
     *
     * @param \Spryker\Shared\Kernel\Communication\Application $application
     *
     * @return \Spryker\Shared\Kernel\Communication\Application
     */
    private function registerServiceProvider(LegacyApplication $application): LegacyApplication
    {
        $serviceProviders = $this->getServiceProvider();
        foreach ($serviceProviders as $serviceProvider) {
            if (!($serviceProvider instanceof ServiceProviderInterface)) {
                $serviceProvider = new $serviceProvider();
            }
            $application->register($serviceProvider);
        }

        return $application;
    }

    /**
     * @param \Spryker\Shared\Application\Application $application
     *
     * @return \Spryker\Shared\Application\Application
     */
    private function registerApplicationPlugins(Application $application): Application
    {
        $applicationPlugins = $this->getApplicationPlugins();
        foreach ($applicationPlugins as $applicationPlugin) {
            if (!($applicationPlugin instanceof ApplicationPluginInterface)) {
                $applicationPlugin = new $applicationPlugin();
            }
            $application->registerApplicationPlugin($applicationPlugin);
        }

        return $application;
    }

    /**
     * @return array
     */
    private function getApplicationPlugins()
    {
        $defaultApplicationPlugins = $this->getDefaultApplicationPlugins();

        return array_merge($defaultApplicationPlugins, $this->additionalApplicationPlugins);
    }

    /**
     * @return array
     */
    private function getDefaultApplicationPlugins()
    {
        return [];
    }

    /**
     * @deprecated Please switch to `Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface`.
     *
     * @return array
     */
    private function getServiceProvider()
    {
        $defaultServiceProviders = $this->getDefaultServiceProvider();

        return array_merge($defaultServiceProviders, $this->additionalServiceProvider);
    }

    /**
     * @deprecated Please switch to `Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface`.
     *
     * @return array
     */
    private function getDefaultServiceProvider()
    {
        return [
            new FormServiceProvider(),
            new HttpFragmentServiceProvider(),
            new ServiceControllerServiceProvider(),
            new ValidatorServiceProvider(),
            new SessionServiceProvider(),
            new TwigServiceProvider(),
        ];
    }

    /**
     * @return string
     */
    private function getCurrentLocale()
    {
        $store = Store::getInstance();

        return $store->getCurrentLocale();
    }
}
