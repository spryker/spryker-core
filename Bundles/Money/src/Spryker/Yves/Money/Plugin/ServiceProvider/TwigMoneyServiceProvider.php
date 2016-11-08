<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Money\Plugin\ServiceProvider;

use Generated\Shared\Transfer\MoneyTransfer;
use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Shared\Money\Formatter\MoneyFormatterCollection;
use Spryker\Yves\Kernel\AbstractPlugin;
use Twig_SimpleFilter;

/**
 * @method \Spryker\Yves\Money\MoneyFactory getFactory()
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
        $moneyFactory = $this->getFactory();

        $filter = new Twig_SimpleFilter('money', function ($money, $withSymbol = true) use ($moneyFactory) {
            if (!($money instanceof MoneyTransfer)) {
                if (is_int($money)) {
                    $money = $moneyFactory->createMoneyBuilder()->fromInteger($money);
                }

                if (is_string($money)) {
                    $money = $moneyFactory->createMoneyBuilder()->fromString($money);
                }

                if (is_float($money)) {
                    $money = $moneyFactory->createMoneyBuilder()->fromFloat($money);
                }
            }

            if ($withSymbol) {
                return $moneyFactory->createMoneyFormatter()->format($money, MoneyFormatterCollection::FORMATTER_WITH_SYMBOL);
            }

            return $moneyFactory->createMoneyFormatter()->format($money, MoneyFormatterCollection::FORMATTER_WITHOUT_SYMBOL);
        });

        return $filter;
    }

}
