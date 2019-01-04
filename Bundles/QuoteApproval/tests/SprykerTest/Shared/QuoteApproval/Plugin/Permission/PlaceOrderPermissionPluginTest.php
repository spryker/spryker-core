<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\QuoteApproval\Plugin\Permission;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Spryker\Shared\PermissionExtension\Dependency\Plugin\ExecutablePermissionPluginInterface;
use Spryker\Shared\QuoteApproval\Plugin\Permission\PlaceOrderPermissionPlugin;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Shared
 * @group QuoteApproval
 * @group Plugin
 * @group Permission
 * @group PlaceOrderPermissionPluginTest
 * Add your own group annotations below this line
 */
class PlaceOrderPermissionPluginTest extends Unit
{
    protected const FIELD_MULTI_CURRENCY = 'multi_currency';
    protected const CURRENCY_CODE = 'EUR';
    protected const CENT_AMOUNT = 100;

    /**
     * @return void
     */
    public function testCanWithValidDataReturnTrue(): void
    {
        $configuration[static::FIELD_MULTI_CURRENCY][static::CURRENCY_CODE] = static::CENT_AMOUNT;
        $quoteTransfer = $this->createQuoteTransfer();

        $placeOrderPermissionPlugin = $this->createPlaceOrderPermissionPlugin();
        $result = $placeOrderPermissionPlugin->can($configuration, $quoteTransfer);

        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testCanWithNullConfigurationCentAmountDataReturnTrue(): void
    {
        $configuration[static::FIELD_MULTI_CURRENCY][static::CURRENCY_CODE] = null;
        $quoteTransfer = $this->createQuoteTransfer();

        $placeOrderPermissionPlugin = $this->createPlaceOrderPermissionPlugin();
        $result = $placeOrderPermissionPlugin->can($configuration, $quoteTransfer);

        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testCanWithZeroConfigurationCentAmountDataReturnFalse(): void
    {
        $configuration[static::FIELD_MULTI_CURRENCY][static::CURRENCY_CODE] = 0;
        $quoteTransfer = $this->createQuoteTransfer();

        $placeOrderPermissionPlugin = $this->createPlaceOrderPermissionPlugin();
        $result = $placeOrderPermissionPlugin->can($configuration, $quoteTransfer);

        $this->assertFalse($result);
    }

    /**
     * @return void
     */
    public function testCanWithEmptyQuoteReturnFalse(): void
    {
        $configuration[static::FIELD_MULTI_CURRENCY][static::CURRENCY_CODE] = static::CENT_AMOUNT;

        $placeOrderPermissionPlugin = $this->createPlaceOrderPermissionPlugin();
        $result = $placeOrderPermissionPlugin->can($configuration, null);

        $this->assertFalse($result);
    }

    /**
     * @return void
     */
    public function testCanWithLessGrandTotalAmountReturnFalse(): void
    {
        $configuration[static::FIELD_MULTI_CURRENCY][static::CURRENCY_CODE] = static::CENT_AMOUNT - 1;
        $quoteTransfer = $this->createQuoteTransfer();

        $placeOrderPermissionPlugin = $this->createPlaceOrderPermissionPlugin();
        $result = $placeOrderPermissionPlugin->can($configuration, $quoteTransfer);

        $this->assertFalse($result);
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteTransfer(): QuoteTransfer
    {
        $totalsTransfer = (new TotalsTransfer())->setGrandTotal(static::CENT_AMOUNT);
        $currencyTransfer = (new CurrencyTransfer())->setCode(static::CURRENCY_CODE);
        $quoteTransfer = (new QuoteTransfer())
            ->setTotals($totalsTransfer)
            ->setCurrency($currencyTransfer);

        return $quoteTransfer;
    }

    /**
     * @return \Spryker\Shared\PermissionExtension\Dependency\Plugin\ExecutablePermissionPluginInterface
     */
    protected function createPlaceOrderPermissionPlugin(): ExecutablePermissionPluginInterface
    {
        return new PlaceOrderPermissionPlugin();
    }
}
