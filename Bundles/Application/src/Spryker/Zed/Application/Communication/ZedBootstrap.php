<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Application\Communication;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Kernel\Communication\Application;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Application\ApplicationDependencyProvider;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\BundleDependencyProviderResolverAwareTrait;
use Spryker\Zed\Kernel\Communication\Plugin\Pimple;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Kernel\ControllerResolver\ZedFragmentControllerResolver;
use Spryker\Zed\Kernel\Dependency\Injector\DependencyInjector;
use Symfony\Component\HttpFoundation\Request;

class ZedBootstrap
{
    use BundleDependencyProviderResolverAwareTrait;

    /**
     * @var \Spryker\Shared\Kernel\Communication\Application
     */
    protected $application;

    public function __construct()
    {
        $this->application = $this->getBaseApplication();
    }

    /**
     * @return \Spryker\Shared\Kernel\Communication\Application
     */
    public function boot()
    {
        $store = Store::getInstance();
        $this->application['debug'] = Config::get(ApplicationConstants::ENABLE_APPLICATION_DEBUG, false);
        $this->application['locale'] = $store->getCurrentLocale();

        if (Config::get(ApplicationConstants::ENABLE_WEB_PROFILER, false)) {
            $this->application['profiler.cache_dir'] = APPLICATION_ROOT_DIR . '/data/' . $store->getStoreName() . '/cache/profiler';
        }

        $this->enableHttpMethodParameterOverride();
        $this->setUp();

        return $this->application;
    }

    /**
     * @return void
     */
    protected function setUp()
    {
        $this->optimizeApp();

        // For BC
        if ($this->isInternalRequest() && !$this->isAuthenticationEnabled()) {
            $this->registerServiceProviderForInternalRequest();

            return;
        }
        // For BC
        if ($this->isInternalRequest()) {
            $this->registerServiceProviderForInternalRequestWithAuthentication();

            return;
        }

        $this->registerServiceProvider();
    }

    /**
     * @return void
     */
    protected function registerServiceProvider()
    {
        foreach ($this->getServiceProvider() as $provider) {
            $this->application->register($provider);
        }
    }

    /**
     * @return void
     */
    protected function registerServiceProviderForInternalRequest()
    {
        foreach ($this->getInternalCallServiceProvider() as $provider) {
            $this->application->register($provider);
        }
    }

    /**
     * @return void
     */
    protected function registerServiceProviderForInternalRequestWithAuthentication()
    {
        $serviceProviders = $this->getInternalCallServiceProviderWithAuthentication();

        /** @deprecated This added to keep Backward Compatibility and will be removed in major release */
        if (!$serviceProviders) {
            $serviceProviders = $this->getServiceProvider();
        }

        foreach ($serviceProviders as $provider) {
            $this->application->register($provider);
        }
    }

    /**
     * @return \Silex\ServiceProviderInterface[]
     */
    protected function getServiceProvider()
    {
        return $this->getProvidedDependency(ApplicationDependencyProvider::SERVICE_PROVIDER);
    }

    /**
     * @return \Silex\ServiceProviderInterface[]
     */
    protected function getInternalCallServiceProvider()
    {
        return $this->getProvidedDependency(ApplicationDependencyProvider::INTERNAL_CALL_SERVICE_PROVIDER);
    }

    /**
     * @return \Silex\ServiceProviderInterface[]
     */
    protected function getInternalCallServiceProviderWithAuthentication()
    {
        return $this->getProvidedDependency(ApplicationDependencyProvider::INTERNAL_CALL_SERVICE_PROVIDER_WITH_AUTHENTICATION);
    }

    /**
     * @return void
     */
    protected function registerApiServiceProvider()
    {
        foreach ($this->getApiServiceProvider() as $provider) {
            $this->application->register($provider);
        }
    }

    /**
     * @return \Silex\ServiceProviderInterface[]
     */
    protected function getApiServiceProvider()
    {
        return $this->getProvidedDependency(ApplicationDependencyProvider::SERVICE_PROVIDER_API);
    }

    /**
     * @return \Spryker\Shared\Kernel\Communication\Application
     */
    protected function getBaseApplication()
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
    private function unsetSilexExceptionHandler(Application $application)
    {
        unset($application['exception_handler']);
    }

    /**
     * @return void
     */
    protected function optimizeApp()
    {
        $application = $this->application;
        $application['resolver'] = $this->application->share(function () use ($application) {
            return new ZedFragmentControllerResolver($application);
        });
    }

    /**
     * Allow overriding http method. Needed to use the "_method" parameter in forms.
     * This should not be changeable by projects
     *
     * @return void
     */
    private function enableHttpMethodParameterOverride()
    {
        Request::enableHttpMethodParameterOverride();
    }

    /**
     * @param \Spryker\Zed\Kernel\AbstractBundleDependencyProvider $dependencyProvider
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container $container
     */
    protected function provideExternalDependencies(AbstractBundleDependencyProvider $dependencyProvider, Container $container)
    {
        $dependencyProvider->provideBusinessLayerDependencies($container);
        $dependencyProvider->provideCommunicationLayerDependencies($container);
        $dependencyProvider->providePersistenceLayerDependencies($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Dependency\Injector\DependencyInjector $dependencyInjector
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Shared\Kernel\ContainerInterface|\Spryker\Zed\Kernel\Container
     */
    protected function injectExternalDependencies(DependencyInjector $dependencyInjector, Container $container)
    {
        $container = $dependencyInjector->injectCommunicationLayerDependencies($container);

        return $container;
    }

    /**
     * @return bool
     */
    protected function isInternalRequest()
    {
        return array_key_exists('HTTP_X_INTERNAL_REQUEST', $_SERVER);
    }

    /**
     * For performance reasons you can disable this in your project
     * Set `AuthConstants::AUTH_ZED_ENABLED` in your config to false
     * if you don't need authentication enabled.
     *
     * If set to false only a subset of ServiceProvider will be added.
     *
     * @return bool
     */
    protected function isAuthenticationEnabled()
    {
        return true;
    }
}
