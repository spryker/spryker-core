<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Application\Communication;

use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Shared\Kernel\Store;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerEngine\Shared\Application\Communication\Application;
use SprykerEngine\Shared\Application\Communication\Bootstrap;
use SprykerFeature\Shared\Library\Config;
use SprykerFeature\Shared\Library\DataDirectory;
use SprykerFeature\Shared\Library\Environment;
use SprykerFeature\Shared\System\SystemConfig;
use SprykerFeature\Zed\Application\Communication\Plugin\Pimple;
use SprykerFeature\Zed\Kernel\Communication\Plugin\GatewayServiceProviderPlugin;
use SprykerFeature\Zed\Session\Communication\Plugin\ServiceProvider\SessionServiceProvider;
use Symfony\Component\HttpFoundation\Request;

abstract class ZedBootstrap extends Bootstrap
{

    /**
     * @return Application
     */
    protected function getBaseApplication()
    {
        $application = new Application();

        Pimple::setApplication($application);

        return $application;
    }

    /**
     * @param Application $app
     */
    protected function beforeBoot(Application $app)
    {
        $app['locale'] = Store::getInstance()->getCurrentLocale();
        if (Environment::isDevelopment()) {
            $app['profiler.cache_dir'] = DataDirectory::getLocalStoreSpecificPath('cache/profiler');
        }
    }

    /**
     * @param Application $app
     */
    protected function afterBoot(Application $app)
    {
        $app['monolog.level'] = Config::get(SystemConfig::LOG_LEVEL);
    }

    /**
     * @return AutoCompletion
     */
    protected function getLocator()
    {
        return Locator::getInstance();
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

    /**
     * @return string
     */
    protected function getNavigation()
    {
        $request = Request::createFromGlobals();

        return $this->getLocator()
            ->application()
            ->pluginNavigation()
            ->buildNavigation($request->getPathInfo());
    }

    /**
     * @return string
     */
    protected function getUsername()
    {
        $username = '';

        $userFacade = $this->getLocator()->user()->facade();
        if ($userFacade->hasCurrentUser()) {
            $user = $userFacade->getCurrentUser();
            $username = sprintf('%s %s', $user->getFirstName(), $user->getLastName());
        }

        return $username;
    }

}
