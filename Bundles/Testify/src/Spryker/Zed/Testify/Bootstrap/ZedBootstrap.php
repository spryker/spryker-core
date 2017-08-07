<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Testify\Bootstrap;

use Silex\Provider\FormServiceProvider;
use Silex\Provider\HttpFragmentServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Silex\ServiceProviderInterface;
use Spryker\Shared\Application\ServiceProvider\FormFactoryServiceProvider;
use Spryker\Shared\Kernel\Communication\Application;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Application\Communication\Plugin\ServiceProvider\HeaderServiceProvider;
use Spryker\Zed\Application\Communication\Plugin\ServiceProvider\MvcRoutingServiceProvider;
use Spryker\Zed\Application\Communication\Plugin\ServiceProvider\RequestServiceProvider;
use Spryker\Zed\Application\Communication\Plugin\ServiceProvider\RoutingServiceProvider;
use Spryker\Zed\Application\Communication\Plugin\ServiceProvider\SubRequestServiceProvider;
use Spryker\Zed\Application\Communication\Plugin\ServiceProvider\TranslationServiceProvider;
use Spryker\Zed\Application\Communication\Plugin\ServiceProvider\UrlGeneratorServiceProvider;
use Spryker\Zed\Gui\Communication\Plugin\ServiceProvider\GuiTwigExtensionServiceProvider;
use Spryker\Zed\Kernel\Communication\Plugin\Pimple;
use Spryker\Zed\Kernel\ControllerResolver\ZedFragmentControllerResolver;
use Spryker\Zed\Session\Communication\Plugin\ServiceProvider\SessionServiceProvider as SprykerSessionServiceProvider;
use Spryker\Zed\Twig\Communication\Plugin\ServiceProvider\TwigServiceProvider as SprykerTwigServiceProvider;
use Spryker\Zed\ZedNavigation\Communication\Plugin\ServiceProvider\ZedNavigationServiceProvider;
use Spryker\Zed\ZedRequest\Communication\Plugin\GatewayControllerListenerPlugin;
use Spryker\Zed\ZedRequest\Communication\Plugin\GatewayServiceProviderPlugin;
use Symfony\Component\HttpFoundation\Request;

class ZedBootstrap
{

    /**
     * @var \Silex\ServiceProviderInterface[]
     */
    private $additionalServiceProvider;

    /**
     * @var \Silex\Application
     */
    private $application;

    /**
     * @param \Silex\ServiceProviderInterface[] $additionalServiceProvider
     */
    public function __construct(array $additionalServiceProvider)
    {
        $this->additionalServiceProvider = $additionalServiceProvider;
    }

    /**
     * @return mixed
     */
    public function boot()
    {
        $application = $this->getApplication();
        $application['debug'] = true;
        $application['locale'] = $this->getCurrentLocale();

        $this->addFragmentControllerResolver();
        $this->enableHttpMethodParameterOverride();

        $this->registerServiceProvider();
        $application['session.test'] = true;

        $this->addVariablesToTwig();

        return $this->application;
    }

    /**
     * @return \Silex\Application|\Spryker\Shared\Kernel\Communication\Application
     */
    private function getApplication()
    {
        $application = new Application();
        $this->unsetSilexExceptionHandler($application);
        Pimple::setApplication($application);

        $this->application = $application;

        return $this->application;
    }

    /**
     * @return void
     */
    protected function addFragmentControllerResolver()
    {
        $application = $this->application;
        $application['resolver'] = $application->share(function () use ($application) {
            return new ZedFragmentControllerResolver($application);
        });
    }

    /**
     * @return void
     */
    private function registerServiceProvider()
    {
        $serviceProviders = $this->getServiceProvider();
        foreach ($serviceProviders as $serviceProvider) {
            if (!($serviceProvider instanceof ServiceProviderInterface)) {
                $serviceProvider = $serviceProvider = new $serviceProvider;
            }
            $this->application->register($serviceProvider);
        }
    }

    /**
     * @return array
     */
    private function getServiceProvider()
    {
        $defaultServiceProviders = $this->getDefaultServiceProvider();

        return array_merge($defaultServiceProviders, $this->additionalServiceProvider);
    }

    /**
     * @return array
     */
    private function getDefaultServiceProvider()
    {
        return [
            new SessionServiceProvider(),
            new SprykerSessionServiceProvider(),
            new TwigServiceProvider(),
            new SprykerTwigServiceProvider(),
            new FormServiceProvider(),
            new HttpFragmentServiceProvider(),
            new ServiceControllerServiceProvider(),
            new ValidatorServiceProvider(),
            new HeaderServiceProvider(),
            new MvcRoutingServiceProvider(),
            new RequestServiceProvider(),
            new RoutingServiceProvider(),
            new SubRequestServiceProvider(),
            new TranslationServiceProvider(),
            new UrlGeneratorServiceProvider(),
            new GuiTwigExtensionServiceProvider(),
            $this->getGatewayServiceProvider(),
            new FormFactoryServiceProvider(),
            new ZedNavigationServiceProvider(),
        ];
    }

    /**
     * @return \Spryker\Zed\ZedRequest\Communication\Plugin\GatewayServiceProviderPlugin
     */
    private function getGatewayServiceProvider()
    {
        $controllerListener = new GatewayControllerListenerPlugin();
        $serviceProvider = new GatewayServiceProviderPlugin();
        $serviceProvider->setControllerListener($controllerListener);

        return $serviceProvider;
    }

    /**
     * @return void
     */
    protected function addVariablesToTwig()
    {
        $application = $this->application;
        $application['twig.global.variables'] = $application->share(
            $application->extend('twig.global.variables', function (array $variables) {
                $variables += [
                    'environment' => APPLICATION_ENV,
                    'store' => Store::getInstance()->getStoreName(),
                    'title' => 'Testify | Zed | ' . ucfirst(APPLICATION_ENV),
                    'currentController' => get_class($this),
                ];

                return $variables;
            })
        );
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
     * @param \Spryker\Shared\Kernel\Communication\Application $application
     *
     * @return void
     */
    private function unsetSilexExceptionHandler(Application $application)
    {
        unset($application['exception_handler']);
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
