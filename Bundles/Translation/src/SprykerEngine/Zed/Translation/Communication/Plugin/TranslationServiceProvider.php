<?php

namespace SprykerEngine\Zed\Translation\Communication\Plugin;

use Silex\Application;
use SprykerEngine\Shared\Kernel\Store;
use SprykerEngine\Zed\Kernel\Locator;

class TranslationServiceProvider implements TranslationServiceProviderInterface
{
    /**
     * Registers services on the given app.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Application $app
     */
    public function register(Application $app)
    {
        $currentLanguage = Store::getInstance()->getCurrentLanguage();

        $app['translator'] = Locator::getInstance()->translation()->facade()->getTranslator($currentLanguage);
    }

    /**
     * Bootstraps the application.
     *
     * This method is called after all services are registered
     * and should be used for "dynamic" configuration (whenever
     * a service must be requested).
     *
     * @param Application $app
     */
    public function boot(Application $app)
    {
    }
}