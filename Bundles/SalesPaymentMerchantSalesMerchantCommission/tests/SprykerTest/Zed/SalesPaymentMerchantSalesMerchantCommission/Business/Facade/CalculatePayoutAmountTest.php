<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesPaymentMerchantSalesMerchantCommission\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use Spryker\Zed\SalesPaymentMerchantSalesMerchantCommission\Business\Exception\BaseAmountFieldNotSetException;
use SprykerTest\Zed\SalesPaymentMerchantSalesMerchantCommission\SalesPaymentMerchantSalesMerchantCommissionBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesPaymentMerchantSalesMerchantCommission
 * @group Business
 * @group Facade
 * @group CalculatePayoutAmountTest
 * Add your own group annotations below this line
 */
class CalculatePayoutAmountTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\SalesPaymentMerchantSalesMerchantCommission\SalesPaymentMerchantSalesMerchantCommissionBusinessTester
     */
    protected SalesPaymentMerchantSalesMerchantCommissionBusinessTester $tester;

    /**
     * @dataProvider testCalculatePayoutAmountBasedOnConfigurationDataProvider
     *
     * @param int $sumPriceToPayAggregation
     * @param int $merchantCommissionAmountFullAggregation
     * @param int $expectedPayoutAmount
     * @param bool $isGrossMode
     * @param bool $isTaxDeductionEnabled
     *
     * @return void
     */
    public function testCalculatePayoutAmountBasedOnConfiguration(
        int $sumPriceToPayAggregation,
        int $merchantCommissionAmountFullAggregation,
        int $expectedPayoutAmount,
        bool $isGrossMode,
        bool $isTaxDeductionEnabled
    ): void {
        // Arrange
        $itemTransfer = (new ItemTransfer())
            ->setSumPriceToPayAggregation($sumPriceToPayAggregation)
            ->setMerchantCommissionAmountFullAggregation($merchantCommissionAmountFullAggregation)
            ->setSumTaxAmountFullAggregation(100);

        $orderTransfer = $this->tester->createOrderTransfer($isGrossMode);
        $this->tester->mockConfigMethod('isTaxDeductionEnabledForStoreAndPriceMode', $isTaxDeductionEnabled);

        // Act
        $calculatedPayoutAmount = $this->tester->getFacade()->calculatePayoutAmount($itemTransfer, $orderTransfer);

        // Assert
        $this->assertEquals(
            $expectedPayoutAmount,
            $calculatedPayoutAmount,
            'The calculated payout amount does not match the expected value.',
        );
    }

    /**
     * @return void
     */
    public function testCalculatePayoutAmountThrowsExceptionWhenTaxIsNotProvided(): void
    {
        // Arrange
        $itemTransfer = $this->tester->createItemTransfer(false)
            ->setSumTaxAmountFullAggregation(null)
            ->setSumPriceToPayAggregation(1);

        $orderTransfer = $this->tester->createOrderTransfer(true);
        $this->tester->mockConfigMethod('isTaxDeductionEnabledForStoreAndPriceMode', true);

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->tester->getFacade()->calculatePayoutAmount($itemTransfer, $orderTransfer);
    }

    /**
     * @dataProvider baseAmountFieldNotProvidedDataProvider
     *
     * @param bool $isGrossMode
     *
     * @return void
     */
    public function testCalculatePayoutAmountThrowsExceptionWhenBaseAmountFieldIsNotProvided(
        bool $isGrossMode
    ): void {
        // Arrange
        $itemTransfer = $this->tester->createItemTransfer($isGrossMode);
        $orderTransfer = $this->tester->createOrderTransfer();
        $this->tester->mockConfigMethod('getBaseAmountFieldForGrossMode', '');
        $this->tester->mockConfigMethod('getBaseAmountFieldForNetMode', '');

        // Assert
        $this->expectException(BaseAmountFieldNotSetException::class);

        // Act
        $this->tester->getFacade()->calculatePayoutAmount($itemTransfer, $orderTransfer);
    }

    /**
     * @return array<string, array<int, bool|int>
     */
    public function testCalculatePayoutAmountBasedOnConfigurationDataProvider(): array
    {
        return [
            'Gross mode with no commission and not tax deduction' => [1000, 0, 1000, true, false],
            'Gross mode with positive commission and not tax deduction' => [1000, 100, 900, true, false],
            'Gross mode with no commission but with tax deduction' => [1000, 0, 900, true, true],
            'Gross mode with positive commission and tax deduction' => [1000, 100, 800, true, true],
            'Net mode with no commission and no tax deduction' => [1000, 0, 1000, false, false],
            'Net mode with positive commission and no tax deduction' => [1000, 100, 900, false, false],
            'Net mode with no commission but with tax deduction' => [1000, 0, 900, false, true],
            'Net mode with positive commission and tax deduction' => [1000, 100, 800, false, true],
        ];
    }

    /**
     * @return array<string, list<bool>>
     */
    public function baseAmountFieldNotProvidedDataProvider(): array
    {
        return [
            'Gross mode without base amount field' => [
                true,
            ],
            'Net mode without base amount field' => [
                false,
            ],
        ];
    }
}
