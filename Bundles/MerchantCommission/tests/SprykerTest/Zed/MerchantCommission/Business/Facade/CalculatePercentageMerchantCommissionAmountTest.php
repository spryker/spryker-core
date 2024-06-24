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
 * @group CalculatePercentageMerchantCommissionAmountTest
 * Add your own group annotations below this line
 */
class CalculatePercentageMerchantCommissionAmountTest extends Unit
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
        $calculatedAmount = $this->tester->getFacade()->calculatePercentageMerchantCommissionAmount(
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
            'Merchant commission amount 10%, item quantity 1, item price gross mode, store config gross mode' => [
                (new MerchantCommissionBuilder([
                    MerchantCommissionTransfer::AMOUNT => 1000,
                ]))->build(),
                (new MerchantCommissionCalculationRequestItemBuilder([
                    MerchantCommissionCalculationRequestItemTransfer::QUANTITY => 1,
                    MerchantCommissionCalculationRequestItemTransfer::SUM_GROSS_PRICE => 10000,
                ]))->build(),
                (new MerchantCommissionCalculationRequestBuilder([
                    MerchantCommissionCalculationRequestTransfer::STORE => [StoreTransfer::NAME => static::STORE_NAME_DE],
                ]))->build(),
                static::PRICE_MODE_GROSS,
                1000,
            ],
            'Merchant commission amount 10%, item quantity 1, item price without gross mode, store config gross mode' => [
                (new MerchantCommissionBuilder([
                    MerchantCommissionTransfer::AMOUNT => 1000,
                ]))->build(),
                (new MerchantCommissionCalculationRequestItemBuilder([
                    MerchantCommissionCalculationRequestItemTransfer::QUANTITY => 1,
                    MerchantCommissionCalculationRequestItemTransfer::SUM_GROSS_PRICE => null,
                ]))->build(),
                (new MerchantCommissionCalculationRequestBuilder([
                    MerchantCommissionCalculationRequestTransfer::STORE => [StoreTransfer::NAME => static::STORE_NAME_DE],
                ]))->build(),
                static::PRICE_MODE_GROSS,
                0,
            ],
            'Merchant commission amount 7%, item quantity 1, item price gross mode, store config gross mode, commission amount round up' => [
                (new MerchantCommissionBuilder([
                    MerchantCommissionTransfer::AMOUNT => 700,
                ]))->build(),
                (new MerchantCommissionCalculationRequestItemBuilder([
                    MerchantCommissionCalculationRequestItemTransfer::QUANTITY => 1,
                    MerchantCommissionCalculationRequestItemTransfer::SUM_GROSS_PRICE => 50,
                ]))->build(),
                (new MerchantCommissionCalculationRequestBuilder([
                    MerchantCommissionCalculationRequestTransfer::STORE => [StoreTransfer::NAME => static::STORE_NAME_DE],
                ]))->build(),
                static::PRICE_MODE_GROSS,
                4,
            ],
            'Merchant commission amount 10%, item quantity 1, item price net mode, store config net mode' => [
                (new MerchantCommissionBuilder([
                    MerchantCommissionTransfer::AMOUNT => 1000,
                ]))->build(),
                (new MerchantCommissionCalculationRequestItemBuilder([
                    MerchantCommissionCalculationRequestItemTransfer::QUANTITY => 1,
                    MerchantCommissionCalculationRequestItemTransfer::SUM_NET_PRICE => 10000,
                ]))->build(),
                (new MerchantCommissionCalculationRequestBuilder([
                    MerchantCommissionCalculationRequestTransfer::STORE => [StoreTransfer::NAME => static::STORE_NAME_DE],
                ]))->build(),
                static::PRICE_MODE_NET,
                1000,
            ],
            'Merchant commission amount 10%, item quantity 1, item price without net mode, store config net mode' => [
                (new MerchantCommissionBuilder([
                    MerchantCommissionTransfer::AMOUNT => 1000,
                ]))->build(),
                (new MerchantCommissionCalculationRequestItemBuilder([
                    MerchantCommissionCalculationRequestItemTransfer::QUANTITY => 1,
                    MerchantCommissionCalculationRequestItemTransfer::SUM_NET_PRICE => null,
                ]))->build(),
                (new MerchantCommissionCalculationRequestBuilder([
                    MerchantCommissionCalculationRequestTransfer::STORE => [StoreTransfer::NAME => static::STORE_NAME_DE],
                ]))->build(),
                static::PRICE_MODE_NET,
                0,
            ],
            'Merchant commission amount 7%, item quantity 1, item price net mode, store config net mode, commission amount round up' => [
                (new MerchantCommissionBuilder([
                    MerchantCommissionTransfer::AMOUNT => 700,
                ]))->build(),
                (new MerchantCommissionCalculationRequestItemBuilder([
                    MerchantCommissionCalculationRequestItemTransfer::QUANTITY => 1,
                    MerchantCommissionCalculationRequestItemTransfer::SUM_NET_PRICE => 50,
                ]))->build(),
                (new MerchantCommissionCalculationRequestBuilder([
                    MerchantCommissionCalculationRequestTransfer::STORE => [StoreTransfer::NAME => static::STORE_NAME_DE],
                ]))->build(),
                static::PRICE_MODE_NET,
                4,
            ],
        ];
    }
}
