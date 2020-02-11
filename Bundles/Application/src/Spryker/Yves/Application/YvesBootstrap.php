<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Application;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\Application\Application;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Application as SilexApplication;
use Spryker\Yves\Kernel\BundleDependencyProviderResolverAwareTrait;
use Spryker\Yves\Kernel\Container;
use Spryker\Yves\Kernel\Dependency\Injector\DependencyInjectorInterface;

abstract class YvesBootstrap
{
    use BundleDependencyProviderResolverAwareTrait;

    /**
     * @var \Spryker\Yves\Kernel\Application
     */
    protected $application;

    /**
     * @var \Spryker\Yves\Application\ApplicationConfig
     */
    protected $config;

    /**
     * @var \Spryker\Shared\Application\Application|null
     */
    protected $sprykerApplication;

    public function __construct()
    {
        $this->application = new SilexApplication();

        if ($this->application instanceof ContainerInterface) {
            $this->sprykerApplication = new Application($this->application);
        }

        $this->config = new ApplicationConfig();
    }

    /**
     * @return \Spryker\Shared\Application\Application|\Spryker\Yves\Kernel\Application
     */
    public function boot()
    {
        $this->registerServiceProviders();

        if ($this->sprykerApplication !== null) {
            $this->setupApplication();
        }

        $this->registerRouters();
        $this->registerControllerProviders();

        $this->application->boot();

        if ($this->sprykerApplication === null) {
            return $this->application;
        }

        $this->sprykerApplication->boot();

        return $this->sprykerApplication;
    }

    /**
     * @return void
     */
    protected function setupApplication(): void
    {
        foreach ($this->getApplicationPlugins() as $applicationPlugin) {
            $this->sprykerApplication->registerApplicationPlugin($applicationPlugin);
        }
    }

    /**
     * @return \Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface[]
     */
    protected function getApplicationPlugins(): array
    {
        return $this->getProvidedDependency(ApplicationDependencyProvider::PLUGINS_APPLICATION);
    }

    /**
     * @param \Spryker\Yves\Kernel\AbstractBundleDependencyProvider $dependencyProvider
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function provideExternalDependencies(AbstractBundleDependencyProvider $dependencyProvider, Container $container): Container
    {
        $container = $dependencyProvider->provideDependencies($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Dependency\Injector\DependencyInjectorInterface $dependencyInjector
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function injectExternalDependencies(DependencyInjectorInterface $dependencyInjector, Container $container): Container
    {
        return $dependencyInjector->inject($container);
    }

    /**
     * @return void
     */
    protected function registerServiceProviders()
    {
    }

    /**
     * @return void
     */
    protected function registerRouters()
    {
    }

    /**
     * @return void
     */
    protected function registerControllerProviders()
    {
    }
}
