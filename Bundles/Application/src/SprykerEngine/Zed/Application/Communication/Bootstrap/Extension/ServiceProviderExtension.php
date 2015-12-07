<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Application\Communication\Bootstrap\Extension;

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
            new \SprykerEngine\Zed\Propel\Communication\Plugin\ServiceProvider\PropelServiceProvider(),
            new \SprykerFeature\Zed\Auth\Communication\Plugin\ServiceProvider\RedirectAfterLoginProvider(),
            new \SprykerFeature\Zed\Auth\Communication\Plugin\Bootstrap\AuthBootstrapProvider(),
            new \SprykerFeature\Zed\Application\Communication\Plugin\ServiceProvider\RequestServiceProvider(),
            new \SprykerFeature\Zed\Application\Communication\Plugin\ServiceProvider\SslServiceProvider(),
            new ServiceControllerServiceProvider(),
            new \SprykerFeature\Zed\Application\Communication\Plugin\ServiceProvider\RoutingServiceProvider(),
            new \SprykerFeature\Zed\Acl\Communication\Plugin\Bootstrap\AclBootstrapProvider(),
            new ValidatorServiceProvider(),
            new FormServiceProvider(),
            new TwigServiceProvider(),
            new \SprykerFeature\Zed\Application\Communication\Plugin\ServiceProvider\TwigServiceProvider(),
            new \SprykerFeature\Zed\Application\Communication\Plugin\ServiceProvider\EnvironmentInformationServiceProvider(),
            new \SprykerEngine\Zed\Translation\Communication\Plugin\TranslationServiceProvider(),
            $this->getGatewayServiceProvider(),
            new \SprykerFeature\Zed\Application\Communication\Plugin\ServiceProvider\UrlGeneratorServiceProvider(),
            new \SprykerFeature\Zed\Application\Communication\Plugin\ServiceProvider\NewRelicServiceProvider(),
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
        $controllerListener = new \SprykerFeature\Zed\Kernel\Communication\Plugin\GatewayControllerListenerPlugin();
        $serviceProvider = new \SprykerFeature\Zed\Kernel\Communication\Plugin\GatewayServiceProviderPlugin();
        $serviceProvider->setControllerListener($controllerListener);

        return $serviceProvider;
    }

    /**
     * @return SessionServiceProvider
     */
    protected function getSessionServiceProvider()
    {
        $sessionServiceProvider = new \SprykerFeature\Zed\Session\Communication\Plugin\ServiceProvider\SessionServiceProvider();
        $sessionServiceProvider->setClient(
            $this->getLocator()->session()->client()
        );

        return $sessionServiceProvider;
    }

}
