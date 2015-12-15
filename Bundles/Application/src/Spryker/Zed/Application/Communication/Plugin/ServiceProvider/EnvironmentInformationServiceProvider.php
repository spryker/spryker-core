<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Application\Communication\Plugin\ServiceProvider;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Application\Business\Model\Twig\EnvironmentInfo;
use Silex\Application;
use Silex\ServiceProviderInterface;

class EnvironmentInformationServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{

    /**
     * @param Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
    }

    /**
     * @param Application $app
     *
     * @return void
     */
    public function boot(Application $app)
    {
        $twig = $app['twig'];
        $twig->addFunction(new EnvironmentInfo());
    }

}
