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
     * @var \Spryker\Shared\Kernel\Communication\Application
     */
    protected static $application;

    /**
     * @return void
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        Store::getInstance()->setCurrentLocale('de_DE');

        static::initialiseTwigServiceProviderPlugin();
    }

    /**
     * @return void
     */
    public function testIfCurrentCurrencyFunctionProvided()
    {
        $currencyCurrencyFunction = $this->getCurrentCurrencyTwigFunction();

        $this->assertNotFalse($currencyCurrencyFunction);
    }

    /**
     * @return void
     */
    public function testCurrentCurrencyShouldReturnDefaultShopCurrencySymbol()
    {
        $currencyCurrencyFunction = $this->getCurrentCurrencyTwigFunction();

        $currentCurrency = call_user_func($currencyCurrencyFunction->getCallable());

        $this->assertEquals('€', $currentCurrency);
    }

    /**
     * @return void
     */
    public function testCurrentCurrencyWhenIsoCodeProvided()
    {
        $currencyCurrencyFunction = $this->getCurrentCurrencyTwigFunction();

        $currentCurrency = call_user_func($currencyCurrencyFunction->getCallable(), 'USD');

        $this->assertEquals('$', $currentCurrency);
    }

    /**
     * @return void
     */
    protected static function initialiseTwigServiceProviderPlugin()
    {
        $twigCurrencyServiceProvider = static::createTwigCurrencyServiceProvider();
        $application = static::getApplication();
        $twigCurrencyServiceProvider->register($application);
    }

    /**
     * @return \Spryker\Zed\Currency\Communication\Plugin\ServiceProvider\TwigCurrencyServiceProvider
     */
    protected static function createTwigCurrencyServiceProvider()
    {
        return new TwigCurrencyServiceProvider();
    }

    /**
     * @return \Spryker\Shared\Kernel\Communication\Application
     */
    protected static function getApplication()
    {
        if (!static::$application) {
            $application = new Application();
            $application['twig'] = function () {
                return new Environment(new FilesystemLoader());
            };

            static::$application = $application;
        }

        return static::$application;
    }

    /**
     * @return bool|\Twig\TwigFunction
     */
    protected function getCurrentCurrencyTwigFunction()
    {
        return static::getApplication()['twig']->getFunction(TwigCurrencyServiceProvider::CURRENCY_SYMBOL_FUNCTION_NAME);
    }
}
