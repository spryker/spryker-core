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
            $this->getLocator()->propel()->pluginServiceProviderPropelServiceProvider(),
            $this->getLocator()->auth()->pluginServiceProviderRedirectAfterLoginProvider(),
            $this->getLocator()->auth()->pluginBootstrapAuthBootstrapProvider(),
            $this->getLocator()->application()->pluginServiceProviderRequestServiceProvider(),
            $this->getLocator()->application()->pluginServiceProviderSslServiceProvider(),
            new ServiceControllerServiceProvider(),
            $this->getLocator()->application()->pluginServiceProviderRoutingServiceProvider(),
            $this->getLocator()->acl()->pluginBootstrapAclBootstrapProvider(),
            new ValidatorServiceProvider(),
            new FormServiceProvider(),
            new TwigServiceProvider(),
            $this->getLocator()->application()->pluginServiceProviderTwigServiceProvider(),
            $this->getLocator()->application()->pluginServiceProviderEnvironmentInformationServiceProvider(),
            $this->getLocator()->translation()->pluginTranslationServiceProvider(),
            $this->getGatewayServiceProvider(),
            $this->getLocator()->application()->pluginServiceProviderUrlGeneratorServiceProvider(),
            $this->getLocator()->application()->pluginServiceProviderNewRelicServiceProvider(),
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
        $locator = $this->getLocator();
        $controllerListener = $locator->kernel()->pluginGatewayControllerListenerPlugin();
        $serviceProvider = $locator->kernel()->pluginGatewayServiceProviderPlugin();
        $serviceProvider->setControllerListener($controllerListener);

        return $serviceProvider;
    }

    /**
     * @return SessionServiceProvider
     */
    protected function getSessionServiceProvider()
    {
        $sessionServiceProvider = $this->getLocator()->session()->pluginServiceProviderSessionServiceProvider();
        $sessionServiceProvider->setClient(
            $this->getLocator()->session()->client()
        );

        return $sessionServiceProvider;
    }

}
