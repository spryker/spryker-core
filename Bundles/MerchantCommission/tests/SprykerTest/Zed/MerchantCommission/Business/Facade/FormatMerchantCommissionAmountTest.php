<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantCommission\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\MerchantCommissionAmountFormatRequestBuilder;
use Generated\Shared\Transfer\MerchantCommissionAmountFormatRequestTransfer;
use Spryker\Zed\MerchantCommissionExtension\Communication\Dependency\Plugin\MerchantCommissionCalculatorPluginInterface;
use SprykerTest\Zed\MerchantCommission\MerchantCommissionBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantCommission
 * @group Business
 * @group Facade
 * @group FormatMerchantCommissionAmountTest
 * Add your own group annotations below this line
 */
class FormatMerchantCommissionAmountTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_MERCHANT_COMMISSION_CALCULATOR_PLUGIN_TYPE = 'test-calculation-type';

    /**
     * @uses \Spryker\Zed\MerchantCommission\MerchantCommissionDependencyProvider::PLUGINS_MERCHANT_COMMISSION_CALCULATOR
     *
     * @var string
     */
    protected const PLUGINS_MERCHANT_COMMISSION_CALCULATOR = 'PLUGINS_MERCHANT_COMMISSION_CALCULATOR';

    /**
     * @var \SprykerTest\Zed\MerchantCommission\MerchantCommissionBusinessTester
     */
    protected MerchantCommissionBusinessTester $tester;

    /**
     * @return void
     */
    public function testShouldTransformMerchantCommissionAmount(): void
    {
        // Arrange
        $expectedFormattedAmount = '10 %';
        $this->tester->setDependency(static::PLUGINS_MERCHANT_COMMISSION_CALCULATOR, [
            $this->createMerchantCommissionCalculatorPluginMock($expectedFormattedAmount),
        ]);

        $merchantCommissionAmountFormatRequestTransfer = (new MerchantCommissionAmountFormatRequestBuilder([
            MerchantCommissionAmountFormatRequestTransfer::CALCULATOR_TYPE_PLUGIN => static::TEST_MERCHANT_COMMISSION_CALCULATOR_PLUGIN_TYPE,
            MerchantCommissionAmountFormatRequestTransfer::AMOUNT => 1000,
        ]))->build();

        // Act
        $formattedAmount = $this->tester->getFacade()->formatMerchantCommissionAmount(
            $merchantCommissionAmountFormatRequestTransfer,
        );

        // Assert
        $this->assertSame($expectedFormattedAmount, $formattedAmount);
    }

    /**
     * @param string $formattedAmount
     *
     * @return \Spryker\Zed\MerchantCommissionExtension\Communication\Dependency\Plugin\MerchantCommissionCalculatorPluginInterface
     */
    protected function createMerchantCommissionCalculatorPluginMock(string $formattedAmount): MerchantCommissionCalculatorPluginInterface
    {
        $merchantCommissionCalculatorPluginMock = $this->getMockBuilder(MerchantCommissionCalculatorPluginInterface::class)
            ->getMock();
        $merchantCommissionCalculatorPluginMock->expects($this->once())
            ->method('getCalculatorType')
            ->willReturn('test-calculation-type');
        $merchantCommissionCalculatorPluginMock->expects($this->once())
            ->method('formatMerchantCommissionAmount')
            ->willReturn($formattedAmount);

        return $merchantCommissionCalculatorPluginMock;
    }
}
