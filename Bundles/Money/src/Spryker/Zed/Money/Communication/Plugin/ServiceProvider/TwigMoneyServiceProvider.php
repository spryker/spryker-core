<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Money\Communication\Plugin\ServiceProvider;

use Generated\Shared\Transfer\MoneyTransfer;
use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Twig_SimpleFilter;

/**
 * @method \Spryker\Zed\Money\Business\MoneyFacade getFacade()
 */
class TwigMoneyServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
        $app['twig'] = $app->share(
            $app->extend('twig', function (\Twig_Environment $twig) {
                $twig->addFilter($this->getFilter());

                return $twig;
            })
        );
    }

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function boot(Application $app)
    {
    }

    /**
     * @return \Twig_SimpleFilter
     */
    protected function getFilter()
    {
        $moneyFacade = $this->getFacade();

        $filter = new Twig_SimpleFilter('money', function ($money, $withSymbol = true, $isoCode = null) use ($moneyFacade) {
            if (!($money instanceof MoneyTransfer)) {
                if (is_int($money)) {
                    $money = $moneyFacade->fromInteger($money, $isoCode);
                }

                if (is_string($money)) {
                    $money = $moneyFacade->fromString($money, $isoCode);
                }

                if (is_float($money)) {
                    $money = $moneyFacade->fromFloat($money, $isoCode);
                }
            }

            if ($withSymbol) {
                return $moneyFacade->formatWithSymbol($money);
            }

            return $moneyFacade->formatWithoutSymbol($money);
        });

        return $filter;
    }

}
