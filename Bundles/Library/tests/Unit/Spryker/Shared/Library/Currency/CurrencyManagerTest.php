<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Shared\Library\Currency;

use Spryker\Shared\Library\Currency\CurrencyManager;

/**
 * @group Currency
 * @group CurrencyManager
 */
class CurrencyManagerTest extends \PHPUnit_Framework_TestCase
{

    public function dataProvider()
    {
        return [
            [100, '100,00 €', true, 'EUR'],
            ['95.00', '95,00 €', true, 'EUR'],
            ['1090.00', '1.090,00 €', true, 'EUR'],
        ];
    }

    /**
     * @param float $given
     * @param string $expected
     * @param bool $includeSymbol
     * @param string $currencyIsoCode
     *
     * @dataProvider dataProvider
     *
     * @return void
     */
    public function testFormat($given, $expected, $includeSymbol, $currencyIsoCode)
    {
        $currencyManager = CurrencyManager::getInstance();
        $currencyManager->setDefaultCurrencyIso($currencyIsoCode);
        $result = $currencyManager->format($given, $includeSymbol);
        $this->assertSame($expected, $result);
    }

}
