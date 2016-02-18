<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Application\Communication\Bootstrap\Extension;

use Spryker\Zed\Application\Communication\Plugin\ServiceProvider\HeaderServiceProvider;
use Spryker\Zed\Log\Communication\Plugin\ServiceProvider\LogServiceProvider;
use Spryker\Zed\Session\Communication\Plugin\ServiceProvider\SessionServiceProvider as ServiceProviderSessionServiceProvider;
use Spryker\Zed\Kernel\Communication\Plugin\GatewayControllerListenerPlugin;
use Spryker\Zed\Application\Communication\Plugin\ServiceProvider\NewRelicServiceProvider;
use Spryker\Zed\Application\Communication\Plugin\ServiceProvider\UrlGeneratorServiceProvider;
use Spryker\Zed\Application\Communication\Plugin\ServiceProvider\EnvironmentInformationServiceProvider;
use Spryker\Zed\Application\Communication\Plugin\ServiceProvider\TwigServiceProvider as ServiceProviderTwigServiceProvider;
use Spryker\Zed\Acl\Communication\Plugin\Bootstrap\AclBootstrapProvider;
use Spryker\Zed\Application\Communication\Plugin\ServiceProvider\RoutingServiceProvider;
use Spryker\Zed\Application\Communication\Plugin\ServiceProvider\SslServiceProvider;
use Spryker\Zed\Application\Communication\Plugin\ServiceProvider\RequestServiceProvider;
use Spryker\Zed\Auth\Communication\Plugin\Bootstrap\AuthBootstrapProvider;
use Spryker\Zed\Auth\Communication\Plugin\ServiceProvider\RedirectAfterLoginProvider;
use Spryker\Zed\Propel\Communication\Plugin\ServiceProvider\PropelServiceProvider;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\HttpFragmentServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Silex\Provider\WebProfilerServiceProvider;
use Spryker\Shared\Application\Communication\Bootstrap\Extension\ServiceProviderExtensionInterface;
use Spryker\Shared\Application\Communication\Application;
use Spryker\Shared\Config;
use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Zed\Kernel\Communication\Plugin\GatewayServiceProviderPlugin;

class ServiceProviderExtension extends LocatorAwareExtension implements ServiceProviderExtensionInterface
{

    /**
     * @param \Spryker\Shared\Application\Communication\Application $app
     *
     * @return array
     */
    public function getServiceProvider(Application $app)
    {
        $providers = [
            new LogServiceProvider(),
            new SessionServiceProvider(),
            $this->getSessionServiceProvider(),
            new PropelServiceProvider(),
            new RedirectAfterLoginProvider(),
            new AuthBootstrapProvider(),
            new RequestServiceProvider(),
            new SslServiceProvider(),
            new ServiceControllerServiceProvider(),
            new RoutingServiceProvider(),
            new AclBootstrapProvider(),
            new ValidatorServiceProvider(),
            new FormServiceProvider(),
            new TwigServiceProvider(),
            new ServiceProviderTwigServiceProvider(),
            new EnvironmentInformationServiceProvider(),
            $this->getGatewayServiceProvider(),
            new UrlGeneratorServiceProvider(),
            new NewRelicServiceProvider(),
            new HttpFragmentServiceProvider(),
            new HeaderServiceProvider(),
        ];

        if (Config::get(ApplicationConstants::ENABLE_WEB_PROFILER, false)) {
            $providers[] = new WebProfilerServiceProvider();
        }

        return $providers;
    }

    /**
     * @return \Spryker\Zed\Kernel\Communication\Plugin\GatewayServiceProviderPlugin
     */
    protected function getGatewayServiceProvider()
    {
        $controllerListener = new GatewayControllerListenerPlugin();
        $serviceProvider = new GatewayServiceProviderPlugin();
        $serviceProvider->setControllerListener($controllerListener);

        return $serviceProvider;
    }

    /**
     * @return \Silex\Provider\SessionServiceProvider
     */
    protected function getSessionServiceProvider()
    {
        $sessionServiceProvider = new ServiceProviderSessionServiceProvider();
        $sessionServiceProvider->setClient(
            $this->getLocator()->session()->client()
        );

        return $sessionServiceProvider;
    }

}
