<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Discount\Communication\Plugin\DecisionRule;

use Codeception\Test\Unit;
use Spryker\Zed\Discount\Communication\Plugin\DecisionRule\CurrencyDecisionRulePlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Discount
 * @group Communication
 * @group Plugin
 * @group DecisionRule
 * @group CurrencyDecisionRulePluginTest
 * Add your own group annotations below this line
 */
class CurrencyDecisionRulePluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Discount\DiscountCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetQueryStringValueOptionsReturnsAllAvailableCurrencies(): void
    {
        // Arrange
        $currencyDecisionRulePlugin = new CurrencyDecisionRulePlugin();
        $storeTransfers = $this->tester->getLocator()->store()->facade()->getAllStores();

        // Act
        $currencies = $currencyDecisionRulePlugin->getQueryStringValueOptions();

        // Assert
        foreach ($storeTransfers as $storeTransfer) {
            foreach ($storeTransfer->getAvailableCurrencyIsoCodes() as $currencyIsoCode) {
                $this->assertArrayHasKey($currencyIsoCode, $currencies);
            }
        }
    }
}
