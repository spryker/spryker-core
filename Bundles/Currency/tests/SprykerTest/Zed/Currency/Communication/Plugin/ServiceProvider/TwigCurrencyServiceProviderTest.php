<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Currency\Communication\Plugin\ServiceProvider;

use Codeception\Test\Unit;
use Spryker\Shared\Kernel\Communication\Application;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Currency\Communication\Plugin\ServiceProvider\TwigCurrencyServiceProvider;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

/**
 * @deprecated Will be removed without replacement.
 *
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Currency
 * @group Communication
 * @group Plugin
 * @group ServiceProvider
 * @group TwigCurrencyServiceProviderTest
 * Add your own group annotations below this line
 */
class TwigCurrencyServiceProviderTest extends Unit
{
    /**
     * @var \Spryker\Zed\Currency\Communication\Plugin\ServiceProvider\TwigCurrencyServiceProvider
     */
    protected static $twigServiceProvider;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        Store::getInstance()->setCurrentLocale('de_DE');
    }

    /**
     * @return void
     */
    public function testIfCurrentCurrencyFunctionProvided(): void
    {
        $currencyCurrencyFunction = $this->getCurrentCurrencyTwigFunction();

        $this->assertNotFalse($currencyCurrencyFunction);
    }

    /**
     * @return void
     */
    public function testCurrentCurrencyShouldReturnDefaultShopCurrencySymbol(): void
    {
        $currencyCurrencyFunction = $this->getCurrentCurrencyTwigFunction();

        $currentCurrency = call_user_func($currencyCurrencyFunction->getCallable());

        $this->assertSame('€', $currentCurrency);
    }

    /**
     * @return void
     */
    public function testCurrentCurrencyWhenIsoCodeProvided(): void
    {
        $currencyCurrencyFunction = $this->getCurrentCurrencyTwigFunction();

        $currentCurrency = call_user_func($currencyCurrencyFunction->getCallable(), 'USD');

        $this->assertSame('$', $currentCurrency);
    }

    /**
     * @return \Spryker\Shared\Kernel\Communication\Application
     */
    protected function getApplication(): Application
    {
        $application = new Application();
        $application['twig'] = function () {
            return new Environment(new FilesystemLoader());
        };

        return $application;
    }

    /**
     * @return bool|\Twig\TwigFunction
     */
    protected function getCurrentCurrencyTwigFunction()
    {
        $application = $this->getApplication();
        $twigCurrencyServiceProvider = new TwigCurrencyServiceProvider();
        $twigCurrencyServiceProvider->register($application);

        return $application['twig']->getFunction(TwigCurrencyServiceProvider::CURRENCY_SYMBOL_FUNCTION_NAME);
    }
}
