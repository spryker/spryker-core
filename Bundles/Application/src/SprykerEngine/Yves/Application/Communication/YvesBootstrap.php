<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Yves\Application\Communication;

use Generated\Yves\Ide\AutoCompletion;
use SprykerEngine\Shared\Kernel\Store;
use SprykerEngine\Yves\Application\Communication\Plugin\ControllerProviderInterface;
use SprykerEngine\Yves\Kernel\Locator;
use SprykerFeature\Shared\Application\Communication\Application as SharedApplication;
use SprykerEngine\Yves\Application\Communication\Application as YvesApplication;
use SprykerFeature\Shared\Application\Communication\Bootstrap;
use SprykerFeature\Shared\Library\Config;
use SprykerFeature\Shared\System\SystemConfig;
use SprykerFeature\Shared\Yves\YvesConfig;
use Symfony\Component\HttpFoundation\Request;

abstract class YvesBootstrap extends Bootstrap
{

    /**
     * @return ControllerProviderInterface[]
     */
    abstract protected function getControllerProviders();

    /**
     * @return SharedApplication|YvesApplication
     */
    protected function getBaseApplication()
    {
        return new YvesApplication();
    }

    /**
     * @param SharedApplication $app
     */
    protected function addProvidersToApp(SharedApplication $app)
    {
        parent::addProvidersToApp($app);

        foreach ($this->getControllerProviders() as $provider) {
            $app->mount($provider->getUrlPrefix(), $provider);
        }
    }

    /**
     * @param SharedApplication $app
     *
     * @return \Twig_Extension[]
     */
    protected function getTwigExtensions(SharedApplication $app)
    {
        $yvesExtension = $this->getLocator()->twig()->pluginTwigYves();

        return [
            $yvesExtension->getTwigYvesExtension($app),
        ];
    }

    /**
     * @param SharedApplication $app
     *
     * @return array
     */
    protected function globalTemplateVariables(SharedApplication $app)
    {
        return parent::globalTemplateVariables($app);
    }

    /**
     * @return AutoCompletion
     */
    protected function getLocator()
    {
        return Locator::getInstance();
    }

    /**
     * @param SharedApplication $app
     */
    protected function beforeBoot(SharedApplication $app)
    {
        $app['locale'] = Store::getInstance()->getCurrentLocale();
        if (\SprykerFeature_Shared_Library_Environment::isDevelopment()) {
            $app['profiler.cache_dir'] = \SprykerFeature_Shared_Library_Data::getLocalStoreSpecificPath('cache/profiler');
        }
        $proxies = Config::get(YvesConfig::YVES_TRUSTED_PROXIES);

        Request::setTrustedProxies($proxies);
    }

    /**
     * @param SharedApplication $app
     */
    protected function afterBoot(SharedApplication $app)
    {
        $app['monolog.level'] = Config::get(SystemConfig::LOG_LEVEL);
    }

}
