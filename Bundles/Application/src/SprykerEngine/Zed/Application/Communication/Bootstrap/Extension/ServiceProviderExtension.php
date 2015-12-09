<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Application\Communication\Bootstrap\Extension;

use SprykerFeature\Zed\Session\Communication\Plugin\ServiceProvider\SessionServiceProvider as ServiceProviderSessionServiceProvider;
use SprykerFeature\Zed\Kernel\Communication\Plugin\GatewayControllerListenerPlugin;
use SprykerFeature\Zed\Application\Communication\Plugin\ServiceProvider\NewRelicServiceProvider;
use SprykerFeature\Zed\Application\Communication\Plugin\ServiceProvider\UrlGeneratorServiceProvider;
use SprykerEngine\Zed\Translation\Communication\Plugin\TranslationServiceProvider;
use SprykerFeature\Zed\Application\Communication\Plugin\ServiceProvider\EnvironmentInformationServiceProvider;
use SprykerFeature\Zed\Application\Communication\Plugin\ServiceProvider\TwigServiceProvider as ServiceProviderTwigServiceProvider;
use SprykerFeature\Zed\Acl\Communication\Plugin\Bootstrap\AclBootstrapProvider;
use SprykerFeature\Zed\Application\Communication\Plugin\ServiceProvider\RoutingServiceProvider;
use SprykerFeature\Zed\Application\Communication\Plugin\ServiceProvider\SslServiceProvider;
use SprykerFeature\Zed\Application\Communication\Plugin\ServiceProvider\RequestServiceProvider;
use SprykerFeature\Zed\Auth\Communication\Plugin\Bootstrap\AuthBootstrapProvider;
use SprykerFeature\Zed\Auth\Communication\Plugin\ServiceProvider\RedirectAfterLoginProvider;
use SprykerEngine\Zed\Propel\Communication\Plugin\ServiceProvider\PropelServiceProvider;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\HttpFragmentServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Silex\Provider\WebProfilerServiceProvider;
use SprykerEngine\Shared\Application\Communication\Bootstrap\Extension\ServiceProviderExtensionInterface;
use SprykerEngine\Shared\Application\Communication\Application;
use SprykerEngine\Shared\Config;
use SprykerFeature\Shared\Application\ApplicationConfig;
use SprykerFeature\Zed\Kernel\Communication\Plugin\GatewayServiceProviderPlugin;

class ServiceProviderExtension extends LocatorAwareExtension implements ServiceProviderExtensionInterface
{

    /**
     * @param Application $app
     *
     * @return array
     */
    public function getServiceProvider(Application $app)
    {
        $providers = [
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
        ];

        if (Config::get(ApplicationConfig::ENABLE_WEB_PROFILER, false)) {
            $providers[] = new WebProfilerServiceProvider();
        }

        return $providers;
    }

    /**
     * @return GatewayServiceProviderPlugin
     */
    protected function getGatewayServiceProvider()
    {
        $controllerListener = new GatewayControllerListenerPlugin();
        $serviceProvider = new GatewayServiceProviderPlugin();
        $serviceProvider->setControllerListener($controllerListener);

        return $serviceProvider;
    }

    /**
     * @return SessionServiceProvider
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
