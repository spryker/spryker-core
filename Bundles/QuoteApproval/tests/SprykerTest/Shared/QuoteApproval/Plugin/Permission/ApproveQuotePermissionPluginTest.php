<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\QuoteApproval\Plugin\Permission;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Spryker\Shared\PermissionExtension\Dependency\Plugin\ExecutablePermissionPluginInterface;
use Spryker\Shared\QuoteApproval\Plugin\Permission\ApproveQuotePermissionPlugin;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Shared
 * @group QuoteApproval
 * @group Plugin
 * @group Permission
 * @group ApproveQuotePermissionPluginTest
 * Add your own group annotations below this line
 */
class ApproveQuotePermissionPluginTest extends Unit
{
    protected const FIELD_MULTI_CURRENCY = 'store_multi_currency';
    protected const CURRENCY_CODE = 'EUR';
    protected const CENT_AMOUNT = 100;
    protected const CENT_SHIPMENT_COST = 20;
    protected const STORE_NAME = 'DE';

    /**
     * @return void
     */
    public function testCanWithValidDataReturnTrue(): void
    {
        $configuration[static::FIELD_MULTI_CURRENCY][static::STORE_NAME][static::CURRENCY_CODE] = static::CENT_AMOUNT;
        $quoteTransfer = $this->createQuoteTransfer();

        $approveQuotePermissionPlugin = $this->createApproveQuotePermissionPlugin();
        $result = $approveQuotePermissionPlugin->can($configuration, $quoteTransfer);

        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testCanNotIncludeShipmentsInApproverLimit(): void
    {
        $configuration[static::FIELD_MULTI_CURRENCY][static::STORE_NAME][static::CURRENCY_CODE] = static::CENT_AMOUNT;
        $quoteTransfer = $this->createQuoteTransfer();
        $quoteTransfer->getTotals()->setGrandTotal(
            $quoteTransfer->getTotals()->getGrandTotal() + static::CENT_SHIPMENT_COST
        );

        $quoteTransfer->setShipment($this->createShipmentTransfer(static::CENT_SHIPMENT_COST));

        $approveQuotePermissionPlugin = $this->createApproveQuotePermissionPlugin();
        $result = $approveQuotePermissionPlugin->can($configuration, $quoteTransfer);

        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testCanWithNullConfigurationCentAmountDataReturnTrue(): void
    {
        $configuration[static::FIELD_MULTI_CURRENCY][static::STORE_NAME][static::CURRENCY_CODE] = null;
        $quoteTransfer = $this->createQuoteTransfer();

        $approveQuotePermissionPlugin = $this->createApproveQuotePermissionPlugin();
        $result = $approveQuotePermissionPlugin->can($configuration, $quoteTransfer);

        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testCanWithZeroConfigurationCentAmountDataReturnFalse(): void
    {
        $configuration[static::FIELD_MULTI_CURRENCY][static::STORE_NAME][static::CURRENCY_CODE] = 0;
        $quoteTransfer = $this->createQuoteTransfer();

        $approveQuotePermissionPlugin = $this->createApproveQuotePermissionPlugin();
        $result = $approveQuotePermissionPlugin->can($configuration, $quoteTransfer);

        $this->assertFalse($result);
    }

    /**
     * @return void
     */
    public function testCanWithEmptyQuoteReturnFalse(): void
    {
        $configuration[static::FIELD_MULTI_CURRENCY][static::STORE_NAME][static::CURRENCY_CODE] = static::CENT_AMOUNT;

        $approveQuotePermissionPlugin = $this->createApproveQuotePermissionPlugin();
        $result = $approveQuotePermissionPlugin->can($configuration, null);

        $this->assertFalse($result);
    }

    /**
     * @return void
     */
    public function testCanWithLessGrandTotalAmountReturnFalse(): void
    {
        $configuration[static::FIELD_MULTI_CURRENCY][static::STORE_NAME][static::CURRENCY_CODE] = static::CENT_AMOUNT - 1;
        $quoteTransfer = $this->createQuoteTransfer();

        $approveQuotePermissionPlugin = $this->createApproveQuotePermissionPlugin();
        $result = $approveQuotePermissionPlugin->can($configuration, $quoteTransfer);

        $this->assertFalse($result);
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteTransfer(): QuoteTransfer
    {
        $totalsTransfer = (new TotalsTransfer())->setGrandTotal(static::CENT_AMOUNT);
        $currencyTransfer = (new CurrencyTransfer())->setCode(static::CURRENCY_CODE);
        $storeTransfer = (new StoreTransfer())->setName(static::STORE_NAME);
        $quoteTransfer = (new QuoteTransfer())
            ->setTotals($totalsTransfer)
            ->setCurrency($currencyTransfer)
            ->setStore($storeTransfer);

        return $quoteTransfer;
    }

    /**
     * @param int $priceInCents
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer
     */
    protected function createShipmentTransfer(int $priceInCents): ShipmentTransfer
    {
        return (new ShipmentTransfer())
            ->setMethod(
                (new ShipmentMethodTransfer())
                    ->setStoreCurrencyPrice($priceInCents)
            );
    }

    /**
     * @return \Spryker\Shared\PermissionExtension\Dependency\Plugin\ExecutablePermissionPluginInterface
     */
    protected function createApproveQuotePermissionPlugin(): ExecutablePermissionPluginInterface
    {
        return new ApproveQuotePermissionPlugin();
    }
}
