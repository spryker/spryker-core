<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Currency\Communication\Plugin\ServiceProvider;

use PHPUnit_Framework_TestCase;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Application\Communication\Plugin\Pimple;
use Spryker\Zed\Currency\Communication\Plugin\ServiceProvider\TwigCurrencyServiceProvider;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Currency
 * @group Communication
 * @group Plugin
 * @group ServiceProvider
 * @group TwigCurrencyServiceProviderTest
 */
class TwigCurrencyServiceProviderTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var \Spryker\Zed\Currency\Communication\Plugin\ServiceProvider\TwigCurrencyServiceProvider
     */
    protected static $twigServiceProvider;

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
     * @return \Spryker\Shared\Application\Communication\Application
     */
    protected static function getApplication()
    {
        return (new Pimple())->getApplication();
    }

    /**
     * @return bool|\Twig_Function
     */
    protected function getCurrentCurrencyTwigFunction()
    {
        return static::getApplication()['twig']->getFunction(TwigCurrencyServiceProvider::CURRENCY_SYMBOL_FUNCTION_NAME);
    }

}
