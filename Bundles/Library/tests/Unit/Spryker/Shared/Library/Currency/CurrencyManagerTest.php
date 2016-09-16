<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Shared\Library\Currency;

use Spryker\Shared\Library\Currency\CurrencyManager;

/**
 * @group Unit
 * @group Spryker
 * @group Shared
 * @group Library
 * @group Currency
 * @group CurrencyManagerTest
 */
class CurrencyManagerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return array
     */
    public function dataProvider()
    {
        return [
            [100, '100,00 €', true, 'EUR'],
            ['95.00', '95,00 €', true, 'EUR'],
            ['1090.00', '1.090,00 €', true, 'EUR'],
        ];
    }

    /**
     * @dataProvider dataProvider
     *
     * @param float $given
     * @param string $expected
     * @param bool $includeSymbol
     * @param string $currencyIsoCode
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
