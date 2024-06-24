<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantCommission\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\MerchantCommissionBuilder;
use Generated\Shared\DataBuilder\MerchantCommissionCalculationRequestBuilder;
use Generated\Shared\DataBuilder\MerchantCommissionCalculationRequestItemBuilder;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MerchantCommissionAmountTransfer;
use Generated\Shared\Transfer\MerchantCommissionCalculationRequestItemTransfer;
use Generated\Shared\Transfer\MerchantCommissionCalculationRequestTransfer;
use Generated\Shared\Transfer\MerchantCommissionTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use SprykerTest\Zed\MerchantCommission\MerchantCommissionBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantCommission
 * @group Business
 * @group Facade
 * @group CalculateFixedMerchantCommissionAmountTest
 * Add your own group annotations below this line
 */
class CalculateFixedMerchantCommissionAmountTest extends Unit
{
    /**
     * @uses \Spryker\Shared\Calculation\CalculationPriceMode::PRICE_MODE_NET
     *
     * @var string
     */
    protected const PRICE_MODE_NET = 'NET_MODE';

    /**
     * @uses \Spryker\Shared\Calculation\CalculationPriceMode::PRICE_MODE_GROSS
     *
     * @var string
     */
    protected const PRICE_MODE_GROSS = 'GROSS_MODE';

    /**
     * @var string
     */
    protected const STORE_NAME_DE = 'DE';

    /**
     * @var string
     */
    protected const CURRENCY_EUR = 'EUR';

    /**
     * @var string
     */
    protected const CURRENCY_USD = 'USD';

    /**
     * @var \SprykerTest\Zed\MerchantCommission\MerchantCommissionBusinessTester
     */
    protected MerchantCommissionBusinessTester $tester;

    /**
     * @dataProvider calculatesMerchantCommissionAmountAccordingToProvidedStoreConfigurationDataProvider
     *
     * @param \Generated\Shared\Transfer\MerchantCommissionTransfer $merchantCommissionTransfer
     * @param \Generated\Shared\Transfer\MerchantCommissionCalculationRequestItemTransfer $merchantCommissionCalculationRequestItemTransfer
     * @param \Generated\Shared\Transfer\MerchantCommissionCalculationRequestTransfer $merchantCommissionCalculationRequestTransfer
     * @param string $priceMode
     * @param int $expectedAmount
     *
     * @return void
     */
    public function testCalculatesMerchantCommissionAmountAccordingToProvidedStoreConfiguration(
        MerchantCommissionTransfer $merchantCommissionTransfer,
        MerchantCommissionCalculationRequestItemTransfer $merchantCommissionCalculationRequestItemTransfer,
        MerchantCommissionCalculationRequestTransfer $merchantCommissionCalculationRequestTransfer,
        string $priceMode,
        int $expectedAmount
    ): void {
        // Arrange
        $this->tester->mockConfigMethod('getMerchantCommissionPriceModeForStore', $priceMode);

        // Act
        $calculatedAmount = $this->tester->getFacade()->calculateFixedMerchantCommissionAmount(
            $merchantCommissionTransfer,
            $merchantCommissionCalculationRequestItemTransfer,
            $merchantCommissionCalculationRequestTransfer,
        );

        // Assert
        $this->assertSame($expectedAmount, $calculatedAmount);
    }

    /**
     * @return array<string, mixed>
     */
    protected function calculatesMerchantCommissionAmountAccordingToProvidedStoreConfigurationDataProvider(): array
    {
        return [
            'Merchant commission gross amount EUR, item quantity 1, order with EUR, store config gross mode' => [
                (new MerchantCommissionBuilder())->withMerchantCommissionAmount([
                    MerchantCommissionAmountTransfer::GROSS_AMOUNT => 100,
                    MerchantCommissionAmountTransfer::CURRENCY => [CurrencyTransfer::CODE => static::CURRENCY_EUR],
                ])->build(),
                (new MerchantCommissionCalculationRequestItemBuilder([
                    MerchantCommissionCalculationRequestItemTransfer::QUANTITY => 1,
                ]))->build(),
                (new MerchantCommissionCalculationRequestBuilder([
                    MerchantCommissionCalculationRequestTransfer::STORE => [StoreTransfer::NAME => static::STORE_NAME_DE],
                    MerchantCommissionCalculationRequestTransfer::CURRENCY => [CurrencyTransfer::CODE => static::CURRENCY_EUR],
                ]))->build(),
                static::PRICE_MODE_GROSS,
                100,
            ],
            'Merchant commission net amount EUR, item quantity 1, order with EUR, store config net mode' => [
                (new MerchantCommissionBuilder())->withMerchantCommissionAmount([
                    MerchantCommissionAmountTransfer::NET_AMOUNT => 100,
                    MerchantCommissionAmountTransfer::CURRENCY => [CurrencyTransfer::CODE => static::CURRENCY_EUR],
                ])->build(),
                (new MerchantCommissionCalculationRequestItemBuilder([
                    MerchantCommissionCalculationRequestItemTransfer::QUANTITY => 1,
                ]))->build(),
                (new MerchantCommissionCalculationRequestBuilder([
                    MerchantCommissionCalculationRequestTransfer::STORE => [StoreTransfer::NAME => static::STORE_NAME_DE],
                    MerchantCommissionCalculationRequestTransfer::CURRENCY => [CurrencyTransfer::CODE => static::CURRENCY_EUR],
                ]))->build(),
                static::PRICE_MODE_NET,
                100,
            ],
            'Merchant commission gross amount EUR, item quantity 10, order with EUR, store config gross mode' => [
                (new MerchantCommissionBuilder())->withMerchantCommissionAmount([
                    MerchantCommissionAmountTransfer::GROSS_AMOUNT => 100,
                    MerchantCommissionAmountTransfer::CURRENCY => [CurrencyTransfer::CODE => static::CURRENCY_EUR],
                ])->build(),
                (new MerchantCommissionCalculationRequestItemBuilder([
                    MerchantCommissionCalculationRequestItemTransfer::QUANTITY => 10,
                ]))->build(),
                (new MerchantCommissionCalculationRequestBuilder([
                    MerchantCommissionCalculationRequestTransfer::STORE => [StoreTransfer::NAME => static::STORE_NAME_DE],
                    MerchantCommissionCalculationRequestTransfer::CURRENCY => [CurrencyTransfer::CODE => static::CURRENCY_EUR],
                ]))->build(),
                static::PRICE_MODE_GROSS,
                1000,
            ],
            'Merchant commission net amount EUR, item quantity 10, order with EUR, store config net mode' => [
                (new MerchantCommissionBuilder())->withMerchantCommissionAmount([
                    MerchantCommissionAmountTransfer::NET_AMOUNT => 100,
                    MerchantCommissionAmountTransfer::CURRENCY => [CurrencyTransfer::CODE => static::CURRENCY_EUR],
                ])->build(),
                (new MerchantCommissionCalculationRequestItemBuilder([
                    MerchantCommissionCalculationRequestItemTransfer::QUANTITY => 10,
                ]))->build(),
                (new MerchantCommissionCalculationRequestBuilder([
                    MerchantCommissionCalculationRequestTransfer::STORE => [StoreTransfer::NAME => static::STORE_NAME_DE],
                    MerchantCommissionCalculationRequestTransfer::CURRENCY => [CurrencyTransfer::CODE => static::CURRENCY_EUR],
                ]))->build(),
                static::PRICE_MODE_NET,
                1000,
            ],
            'Merchant commission without gross amount EUR, item quantity 1, order with EUR, store config gross mode' => [
                (new MerchantCommissionBuilder())->withMerchantCommissionAmount([
                    MerchantCommissionAmountTransfer::GROSS_AMOUNT => null,
                    MerchantCommissionAmountTransfer::CURRENCY => [CurrencyTransfer::CODE => static::CURRENCY_EUR],
                ])->build(),
                (new MerchantCommissionCalculationRequestItemBuilder([
                    MerchantCommissionCalculationRequestItemTransfer::QUANTITY => 1,
                ]))->build(),
                (new MerchantCommissionCalculationRequestBuilder([
                    MerchantCommissionCalculationRequestTransfer::STORE => [StoreTransfer::NAME => static::STORE_NAME_DE],
                    MerchantCommissionCalculationRequestTransfer::CURRENCY => [CurrencyTransfer::CODE => static::CURRENCY_EUR],
                ]))->build(),
                static::PRICE_MODE_GROSS,
                0,
            ],
            'Merchant commission without net amount EUR, item quantity 1, order with EUR, store config net mode' => [
                (new MerchantCommissionBuilder())->withMerchantCommissionAmount([
                    MerchantCommissionAmountTransfer::NET_AMOUNT => null,
                    MerchantCommissionAmountTransfer::CURRENCY => [CurrencyTransfer::CODE => static::CURRENCY_EUR],
                ])->build(),
                (new MerchantCommissionCalculationRequestItemBuilder([
                    MerchantCommissionCalculationRequestItemTransfer::QUANTITY => 1,
                ]))->build(),
                (new MerchantCommissionCalculationRequestBuilder([
                    MerchantCommissionCalculationRequestTransfer::STORE => [StoreTransfer::NAME => static::STORE_NAME_DE],
                    MerchantCommissionCalculationRequestTransfer::CURRENCY => [CurrencyTransfer::CODE => static::CURRENCY_EUR],
                ]))->build(),
                static::PRICE_MODE_NET,
                0,
            ],
            'Merchant commission with gross amount EUR, item quantity 1, order with USD, store config gross mode' => [
                (new MerchantCommissionBuilder())->withMerchantCommissionAmount([
                    MerchantCommissionAmountTransfer::GROSS_AMOUNT => null,
                    MerchantCommissionAmountTransfer::CURRENCY => [CurrencyTransfer::CODE => static::CURRENCY_EUR],
                ])->build(),
                (new MerchantCommissionCalculationRequestItemBuilder([
                    MerchantCommissionCalculationRequestItemTransfer::QUANTITY => 1,
                ]))->build(),
                (new MerchantCommissionCalculationRequestBuilder([
                    MerchantCommissionCalculationRequestTransfer::STORE => [StoreTransfer::NAME => static::STORE_NAME_DE],
                    MerchantCommissionCalculationRequestTransfer::CURRENCY => [
                        CurrencyTransfer::CODE => static::CURRENCY_USD,
                    ],
                ]))->build(),
                static::PRICE_MODE_GROSS,
                0,
            ],
            'Merchant commission with net amount EUR, item quantity 1, order with USD, store config net mode' => [
                (new MerchantCommissionBuilder())->withMerchantCommissionAmount([
                    MerchantCommissionAmountTransfer::NET_AMOUNT => null,
                    MerchantCommissionAmountTransfer::CURRENCY => [CurrencyTransfer::CODE => static::CURRENCY_EUR],
                ])->build(),
                (new MerchantCommissionCalculationRequestItemBuilder([
                    MerchantCommissionCalculationRequestItemTransfer::QUANTITY => 1,
                ]))->build(),
                (new MerchantCommissionCalculationRequestBuilder([
                    MerchantCommissionCalculationRequestTransfer::STORE => [StoreTransfer::NAME => static::STORE_NAME_DE],
                    MerchantCommissionCalculationRequestTransfer::CURRENCY => [CurrencyTransfer::CODE => static::CURRENCY_USD],
                ]))->build(),
                static::PRICE_MODE_NET,
                0,
            ],
        ];
    }
}
