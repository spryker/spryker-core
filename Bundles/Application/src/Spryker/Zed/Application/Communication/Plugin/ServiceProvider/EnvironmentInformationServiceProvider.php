<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Application\Communication\Plugin\ServiceProvider;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Application\Business\Model\Twig\EnvironmentInfo;
use Silex\Application;
use Silex\ServiceProviderInterface;

/**
 * @method \Spryker\Zed\Application\Business\ApplicationFacade getFacade()
 * @method \Spryker\Zed\Application\Communication\ApplicationCommunicationFactory getFactory()
 */
class EnvironmentInformationServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
    }

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function boot(Application $app)
    {
        $twig = $app['twig'];
        $twig->addFunction(new EnvironmentInfo());
    }

}
