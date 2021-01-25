<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Bootstrap;

use Spryker\Client\Session\SessionClient;
use Spryker\Glue\GlueApplication\GlueApplicationConfig;
use Spryker\Glue\GlueApplication\GlueApplicationDependencyProvider;
use Spryker\Glue\GlueApplication\Session\Storage\MockArraySessionStorage;
use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\BundleDependencyProviderResolverAwareTrait;
use Spryker\Glue\Kernel\Container;
use Spryker\Glue\Kernel\Plugin\Pimple;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\Application\Application as SprykerApplication;
use Spryker\Shared\Kernel\Communication\Application;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * @deprecated Use {@link \Spryker\Glue\GlueApplication\Bootstrap\GlueBootstrap} instead.
 * @deprecated Use {@link \Spryker\Shared\Http\Plugin\EventDispatcher\ResponseListenerEventDispatcherPlugin} instead.
 */
abstract class AbstractGlueBootstrap
{
    use BundleDependencyProviderResolverAwareTrait;

    /**
     * @var \Spryker\Shared\Kernel\Communication\Application
     */
    protected $application;

    /**
     * @var \Spryker\Shared\Application\Application
     */
    protected $sprykerApplication;

    /**
     * @var \Spryker\Glue\GlueApplication\GlueApplicationConfig
     */
    protected $config;

    public function __construct()
    {
        $this->application = $this->getBaseApplication();

        if ($this->application instanceof ContainerInterface) {
            $this->sprykerApplication = new SprykerApplication($this->application);
        }

        $this->config = new GlueApplicationConfig();

        $this->setUpSession();
    }

    /**
     * @return \Spryker\Shared\Kernel\Communication\Application
     */
    protected function getBaseApplication(): Application
    {
        $application = new Application();

        $this->unsetSilexExceptionHandler($application);

        Pimple::setApplication($application);

        return $application;
    }

    /**
     * @param \Spryker\Shared\Kernel\Communication\Application $application
     *
     * @return void
     */
    protected function unsetSilexExceptionHandler(Application $application): void
    {
        unset($application['exception_handler']);
    }

    /**
     * @return \Spryker\Shared\Application\Application|\Spryker\Shared\Kernel\Communication\Application
     */
    public function boot()
    {
        $this->registerServiceProviders();

        if ($this->sprykerApplication !== null) {
            $this->setupApplication();
        }

        $this->application->boot();

        if ($this->sprykerApplication === null) {
            return $this->application;
        }

        $this->sprykerApplication->boot();

        return $this->sprykerApplication;
    }

    /**
     * @deprecated Use {@link \Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface}'s instead.
     *
     * @return void
     */
    protected function registerServiceProviders(): void
    {
    }

    /**
     * @return void
     */
    protected function setUpSession(): void
    {
        (new SessionClient())->setContainer(
            new Session(
                new MockArraySessionStorage()
            )
        );
    }

    /**
     * @param \Spryker\Glue\Kernel\AbstractBundleDependencyProvider $dependencyProvider
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function provideExternalDependencies(
        AbstractBundleDependencyProvider $dependencyProvider,
        Container $container
    ): Container {
        $container = $dependencyProvider->provideDependencies($container);

        return $container;
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
        return $this->getProvidedDependency(GlueApplicationDependencyProvider::PLUGINS_APPLICATION);
    }
}
