<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\Currency\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CurrencyTransfer;
use Spryker\Yves\Currency\Plugin\CurrencyPlugin;
use SprykerTest\Service\Container\Helper\ContainerHelperTrait;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Yves
 * @group Currency
 * @group Plugin
 * @group CurrencyPluginTest
 * Add your own group annotations below this line
 */
class CurrencyPluginTest extends Unit
{
    use ContainerHelperTrait;

    /**
     * @var \SprykerTest\Yves\Currency\CurrencyCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->mockStoreClientDependency();
        $this->tester->mockSession();
    }

    /**
     * @return void
     */
    public function testFromIsoCodeShouldReturnCurrencyTransfer(): void
    {
        // Assign
        $currencyPlugin = new CurrencyPlugin();

        // Act
        $currencyTransfer = $currencyPlugin->fromIsoCode($this->tester::CURRENCY_USD);

        // Assert
        $this->assertInstanceOf(CurrencyTransfer::class, $currencyTransfer);
        $this->assertEquals($this->tester::CURRENCY_USD, $currencyTransfer->getCode());
    }

    /**
     * @return void
     */
    public function testGetDefaultShouldReturnCurrencyTransfer(): void
    {
        // Assign
        $currencyPlugin = new CurrencyPlugin();

        // Act
        $currencyTransfer = $currencyPlugin->getCurrent();

        // Assert
        $this->assertInstanceOf(CurrencyTransfer::class, $currencyTransfer);
        $this->assertEquals($this->tester::CURRENCY_USD, $currencyTransfer->getCode());
    }
}
