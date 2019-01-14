<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Bootstrap;

use Spryker\Client\Session\SessionClient;
use Spryker\Glue\GlueApplication\GlueApplicationConfig;
use Spryker\Glue\GlueApplication\GlueApplicationDependencyProvider;
use Spryker\Glue\GlueApplication\Session\Storage\MockArraySessionStorage;
use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Application as SilexApplication;
use Spryker\Glue\Kernel\BundleDependencyProviderResolverAwareTrait;
use Spryker\Glue\Kernel\Container;
use Spryker\Shared\Application\Application;
use Symfony\Component\HttpFoundation\Session\Session;

abstract class AbstractGlueBootstrap
{
    use BundleDependencyProviderResolverAwareTrait;

    /**
     * @var \Spryker\Glue\Kernel\Application
     */
    protected $application;

    /**
     * @var \Spryker\Service\Container\ContainerInterface
     */
    protected $serviceContainer;

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
        $this->serviceContainer
            = $this->application
            = new SilexApplication();

        $this->sprykerApplication = new Application($this->serviceContainer);
        $this->config = new GlueApplicationConfig();

        $this->setUpSession();
    }

    /**
     * @return \Spryker\Shared\Application\Application
     */
    public function boot(): Application
    {
        $this->registerServiceProviders();

        $this->setupApplication();

        $this->application->boot();
        $this->sprykerApplication->boot();

        return $this->sprykerApplication;
    }

    /**
     * @return void
     */
    abstract protected function registerServiceProviders(): void;

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
