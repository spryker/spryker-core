<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\Money\Plugin\ServiceProvider;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MoneyTransfer;
use Silex\Application;
use Spryker\Shared\Kernel\Store;
use Spryker\Yves\Money\Plugin\ServiceProvider\TwigMoneyServiceProvider;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Yves
 * @group Money
 * @group Plugin
 * @group ServiceProvider
 * @group TwigMoneyServiceProviderTest
 * Add your own group annotations below this line
 */
class TwigMoneyServiceProviderTest extends Unit
{
    /**
     * @return void
     */
    public function testRegisterShouldAddFilterToTwig()
    {
        $moneyServiceProvider = new TwigMoneyServiceProvider();
        $application = new Application();
        $application['twig'] = function () {
            return new Environment(new FilesystemLoader());
        };

        $moneyServiceProvider->register($application);
    }

    /**
     * @return void
     */
    public function testBootShouldDoNothing()
    {
        $moneyServiceProvider = new TwigMoneyServiceProvider();
        $application = new Application();
        $moneyServiceProvider->boot($application);
    }

    /**
     * @dataProvider formatTestData
     *
     * @param mixed $input
     * @param string $expected
     * @param string $locale
     * @param bool $withSymbol
     *
     * @return void
     */
    public function testFilterExecution($input, $expected, $locale, $withSymbol = true)
    {
        $moneyServiceProvider = new TwigMoneyServiceProvider();
        $application = new Application();
        $application['twig'] = function () {
            return new Environment(new FilesystemLoader());
        };
        $moneyServiceProvider->register($application);

        /** @var \Twig\Environment $twig */
        $twig = $application['twig'];
        $filter = $twig->getFilter('money');

        $callable = $filter->getCallable();

        Store::getInstance()->setCurrentLocale($locale);

        $result = $callable($input, $withSymbol);
        $this->assertSame($expected, $result);
    }

    /**
     * @return array
     */
    public function formatTestData()
    {
        return [
            [$this->createDeMoneyTransfer(), '10,00 €', 'de_DE'],
            [$this->createDeMoneyTransfer(), '10,00', 'de_DE', false],
            [10.00, '10,00 €', 'de_DE'],
            [1000, '10,00 €', 'de_DE'],
            ['1000', '10,00 €', 'de_DE'],
            [$this->createDeMoneyTransfer(), '€10.00', 'en_US'],
            [10.00, '€10.00', 'en_US'],
            [1000, '€10.00', 'en_US'],
            ['1000', '€10.00', 'en_US'],
            [$this->createJpyMoneyTransfer(), '¥1,000', 'en_US'],
            //[$this->createJpyMoneyTransfer(), '1,000', 'en_US', false], // TODO: this case is broken because intl extension can give different results in different environments.
            [$this->createJpyMoneyTransfer(), '1.000 ¥', 'de_DE'],
            //[$this->createJpyMoneyTransfer(), '1.000', 'de_DE', false], // TODO: this case is broken because intl extension can give different results in different environments.
        ];
    }

    /**
     * @return \Generated\Shared\Transfer\MoneyTransfer
     */
    protected function createDeMoneyTransfer()
    {
        $moneyTransfer = new MoneyTransfer();
        $moneyTransfer->setAmount('1000');
        $currencyTransfer = new CurrencyTransfer();
        $currencyTransfer->setCode('EUR');
        $moneyTransfer->setCurrency($currencyTransfer);

        return $moneyTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\MoneyTransfer
     */
    protected function createJpyMoneyTransfer()
    {
        $moneyTransfer = new MoneyTransfer();
        $moneyTransfer->setAmount('1000');
        $currencyTransfer = new CurrencyTransfer();
        $currencyTransfer->setCode('JPY');
        $moneyTransfer->setCurrency($currencyTransfer);

        return $moneyTransfer;
    }
}
