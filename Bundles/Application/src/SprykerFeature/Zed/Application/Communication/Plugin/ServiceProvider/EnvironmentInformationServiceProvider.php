<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Application\Communication\Plugin\ServiceProvider;

use SprykerFeature\Zed\Application\Business\Model\Twig\EnvironmentInfo;
use Silex\Application;
use Silex\ServiceProviderInterface;

class EnvironmentInformationServiceProvider implements ServiceProviderInterface
{

    /**
     * @param Application $app
     */
    public function register(Application $app)
    {
    }

    /**
     * @param Application $app
     */
    public function boot(Application $app)
    {
        $twig = $app['twig'];
        $twig->addFunction(new EnvironmentInfo());
    }

}
