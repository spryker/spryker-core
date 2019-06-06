<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Application\Communication\Plugin\ServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Twig\Environment;

/**
 * @method \Spryker\Zed\Application\Business\ApplicationFacadeInterface getFacade()
 * @method \Spryker\Zed\Application\Communication\ApplicationCommunicationFactory getFactory()
 * @method \Spryker\Zed\Application\ApplicationConfig getConfig()
 */
class ApplicationTwigServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{
    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
        $app['twig'] = $app->share(
            $app->extend('twig', function (Environment $twig) {

                $this->registerTwigFunctions($twig);
                $this->registerTwigFilters($twig);

                return $twig;
            })
        );
    }

    /**
     * @param \Twig\Environment $twig
     *
     * @return void
     */
    protected function registerTwigFunctions(Environment $twig)
    {
        foreach ($this->getFactory()->getApplicationTwigFunctions() as $function) {
            $twig->addFunction($function);
        }
    }

    /**
     * @param \Twig\Environment $twig
     *
     * @return void
     */
    protected function registerTwigFilters(Environment $twig)
    {
        foreach ($this->getFactory()->getApplicationTwigFilters() as $filter) {
            $twig->addFilter($filter);
        }
    }

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function boot(Application $app)
    {
    }
}
