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
use Twig\Environment;
use Twig\TwigFilter;
use Twig\TwigFunction;

/**
 * @deprecated Use {@link \Spryker\Zed\Money\Communication\Plugin\Twig\MoneyTwigPlugin} instead.
 *
 * @method \Spryker\Zed\Money\Business\MoneyFacadeInterface getFacade()
 * @method \Spryker\Zed\Money\Communication\MoneyCommunicationFactory getFactory()
 * @method \Spryker\Zed\Money\MoneyConfig getConfig()
 */
class TwigMoneyServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{
    /**
     * @var string
     */
    public const FUNCTION_NAME_MONEY_COLLECTION = 'form_money_collection';

    /**
     * @var string
     */
    public const TEMPLATE_PATH_MONEY_TABLE = '@Money/Form/Type/money_table.twig';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
        $app['twig'] = $app->share(
            $app->extend('twig', function (Environment $twig) {
                $twig->addFilter($this->getFilter());
                $twig->addFunction($this->getMoneyFormTableFunction($twig));

                return $twig;
            }),
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
    protected function getFilter()
    {
        $moneyFacade = $this->getFacade();

        $filter = new TwigFilter('money', function ($money, $withSymbol = true, $isoCode = null) use ($moneyFacade) {
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

    /**
     * @param \Twig\Environment $twig
     *
     * @return \Twig\TwigFunction
     */
    public function getMoneyFormTableFunction(Environment $twig)
    {
        $options = ['is_safe' => ['html']];

        return new TwigFunction(
            static::FUNCTION_NAME_MONEY_COLLECTION,
            function ($moneyValueFormViewCollection) use ($twig) {
                return $twig->render(
                    static::TEMPLATE_PATH_MONEY_TABLE,
                    [
                        'moneyValueFormViewCollection' => $moneyValueFormViewCollection,
                    ],
                );
            },
            $options,
        );
    }
}
