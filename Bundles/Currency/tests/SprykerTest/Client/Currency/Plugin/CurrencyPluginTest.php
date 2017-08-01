<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Currency\Plugin;

use Generated\Shared\Transfer\CurrencyTransfer;
use PHPUnit_Framework_TestCase;
use Spryker\Client\Currency\Plugin\CurrencyPlugin;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Client
 * @group Currency
 * @group Plugin
 * @group CurrencyPluginTest
 * Add your own group annotations below this line
 */
class CurrencyPluginTest extends PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testFromIsoCodeShouldReturnCurrencyTransfer()
    {
        $currencyPlugin = new CurrencyPlugin();
        $currencyTransfer = $currencyPlugin->fromIsoCode('EUR');
        $this->assertInstanceOf(CurrencyTransfer::class, $currencyTransfer);
    }

    /**
     * @return void
     */
    public function testGetCurrentShouldReturnCurrencyTransfer()
    {
        $currencyPlugin = new CurrencyPlugin();
        $currencyTransfer = $currencyPlugin->getCurrent();
        $this->assertInstanceOf(CurrencyTransfer::class, $currencyTransfer);
    }

}
