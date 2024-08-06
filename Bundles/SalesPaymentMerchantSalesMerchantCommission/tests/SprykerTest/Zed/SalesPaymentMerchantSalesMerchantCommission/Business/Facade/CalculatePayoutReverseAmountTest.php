<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesPaymentMerchantSalesMerchantCommission\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
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
 * @group CalculatePayoutReverseAmountTest
 * Add your own group annotations below this line
 */
class CalculatePayoutReverseAmountTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\SalesPaymentMerchantSalesMerchantCommission\SalesPaymentMerchantSalesMerchantCommissionBusinessTester
     */
    protected SalesPaymentMerchantSalesMerchantCommissionBusinessTester $tester;

    /**
     * @dataProvider commissionRefundApplicationDataProvider
     *
     * @param int $canceledAmount
     * @param int $merchantCommissionRefundedAmount
     * @param int $expectedPayoutReverseAmount
     * @param bool $isGrossMode
     * @param bool $isTaxDeductionEnabled
     *
     * @return void
     */
    public function testCalculatePayoutReverseAmountForGivenConfiguration(
        int $canceledAmount,
        int $merchantCommissionRefundedAmount,
        int $expectedPayoutReverseAmount,
        bool $isGrossMode,
        bool $isTaxDeductionEnabled = false
    ): void {
        // Arrange
        $itemTransfer = (new ItemTransfer())
            ->setCanceledAmount($canceledAmount)
            ->setMerchantCommissionRefundedAmount($merchantCommissionRefundedAmount)
            ->setTaxAmountAfterCancellation(100);

        $orderTransfer = $this->tester->createOrderTransfer($isGrossMode);
        $this->tester->mockConfigMethod('isTaxDeductionEnabledForStoreAndPriceMode', $isTaxDeductionEnabled);

        // Act
        $result = $this->tester->getFacade()->calculatePayoutReverseAmount($itemTransfer, $orderTransfer);

        // Assert
        $this->assertEquals($expectedPayoutReverseAmount, $result, 'The calculated payout reverse amount with commission refund does not match the expected value.');
    }

    /**
     * @return array<string, list<int, int>>
     */
    public function commissionRefundApplicationDataProvider(): array
    {
        return [
            'Gross mode with no commission refund and no tax deduction' => [1000, 0, 1000, true, false],
            'Gross mode with positive commission refund and no tax deduction' => [1000, 100, 900, true, false],
            'Gross mode with no commission refund and tax deduction' => [1000, 0, 900, true, true],
            'Gross mode with positive commission refund and tax deduction' => [1000, 100, 800, true, true],
            'Net mode with no commission refund and no tax deduction' => [1000, 0, 1000, false, false],
            'Net mode with positive commission refund and no tax deduction' => [1000, 100, 900, false, false],
            'Net mode with positive commission refund and tax deduction' => [1000, 100, 800, false, true],
            'Net mode with no commission refund and tax deduction' => [1000, 0, 900, false, true],
        ];
    }

    /**
     * @return void
     */
    public function testCalculatePayoutReverseAmountThrowsExceptionWhenBaseAmountFieldForReversePayoutNotSet(): void
    {
        // Arrange
        $itemTransfer = new ItemTransfer();
        $orderTransfer = $this->tester->createOrderTransfer();
        $this->tester->mockConfigMethod('getBaseAmountFieldForReversePayout', '');

        // Assert
        $this->expectException(BaseAmountFieldNotSetException::class);

        // Act
        $this->tester->getFacade()->calculatePayoutReverseAmount($itemTransfer, $orderTransfer);
    }
}
