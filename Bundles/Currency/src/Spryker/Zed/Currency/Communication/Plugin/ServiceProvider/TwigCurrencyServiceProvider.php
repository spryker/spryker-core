<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Currency\Communication\Plugin\ServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Twig_SimpleFunction;

/**
 * @method \Spryker\Zed\Currency\Business\CurrencyFacade getFacade()
 */
class TwigCurrencyServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{

    /**
     * Registers services on the given app.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     */
    public function register(Application $app)
    {
        $app['twig'] = $app->share(
            $app->extend('twig', function (\Twig_Environment $twig) {
                $twig->addFunction($this->getFunction());

                return $twig;
            })
        );
    }

    /**
     * Bootstraps the application.
     *
     * This method is called after all services are registered
     * and should be used for "dynamic" configuration (whenever
     * a service must be requested).
     */
    public function boot(Application $app)
    {
    }

    /**
     * @return \Twig_SimpleFunction
     */
    protected function getFunction()
    {
        $function = new Twig_SimpleFunction('currencySymbol', function ($isoCode = null) {

             if ($isoCode) {
                 $currencyTransfer = $this->getFacade()->fromIsoCode($isoCode);
             } else {
                 $currencyTransfer = $this->getFacade()->getCurrent();
             }

             if ($currencyTransfer) {
                 $currencySymbol = $currencyTransfer->getSymbol();
             }

             if (!$currencySymbol) {
                 $currencySymbol = '-';
             }

             return $currencySymbol;
        });

        return $function;
    }
}
