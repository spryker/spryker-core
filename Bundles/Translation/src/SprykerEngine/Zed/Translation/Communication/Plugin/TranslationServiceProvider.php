<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Translation\Communication\Plugin;

use Silex\Application;
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
        $currentLocale = Locator::getInstance()->locale()->facade()->getCurrentLocaleName();

        $app['translator'] = Locator::getInstance()->translation()->facade()->getTranslator($currentLocale);
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
