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
use Twig\Environment;
use Twig\TwigFilter;

/**
 * @deprecated Use `Spryker\Yves\Money\Plugin\Twig\MoneyTwigPlugin` instead.
 *
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
            $app->extend('twig', function (Environment $twig) {
                $twig->addFilter($this->getMoneyFilter());
                $twig->addFilter($this->getMoneyRawFilter());

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
     * @return \Twig\TwigFilter
     */
    protected function getMoneyFilter()
    {
        $moneyFactory = $this->getFactory();

        $filter = new TwigFilter('money', function ($money, $withSymbol = true, $isoCode = null) use ($moneyFactory) {
            if (!($money instanceof MoneyTransfer)) {
                $money = $this->getMoneyTransfer($money, $isoCode);
            }

            if ($withSymbol) {
                return $moneyFactory->createMoneyFormatter()->format($money, MoneyFormatterCollection::FORMATTER_WITH_SYMBOL);
            }

            return $moneyFactory->createMoneyFormatter()->format($money, MoneyFormatterCollection::FORMATTER_WITHOUT_SYMBOL);
        });

        return $filter;
    }

    /**
     * @return \Twig\TwigFilter
     */
    protected function getMoneyRawFilter()
    {
        $moneyFactory = $this->getFactory();

        $filter = new TwigFilter('moneyRaw', function ($money, $isoCode = null) use ($moneyFactory) {
            if (!($money instanceof MoneyTransfer)) {
                $money = $this->getMoneyTransfer($money, $isoCode);
            }

            return $moneyFactory->createIntegerToDecimalConverter()->convert((int)$money->getAmount());
        });

        return $filter;
    }

    /**
     * @param int|string|float $money
     * @param string|null $isoCode
     *
     * @return \Generated\Shared\Transfer\MoneyTransfer
     */
    protected function getMoneyTransfer($money, $isoCode = null)
    {
        $moneyFactory = $this->getFactory();

        if (is_int($money)) {
            $money = $moneyFactory->createMoneyBuilder()->fromInteger($money, $isoCode);
        }

        if (is_string($money)) {
            $money = $moneyFactory->createMoneyBuilder()->fromString($money, $isoCode);
        }

        if (is_float($money)) {
            $money = $moneyFactory->createMoneyBuilder()->fromFloat($money, $isoCode);
        }

        return $money;
    }
}
