<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantCommission\Communication\Plugin\MerchantCommission;

use Codeception\Test\Unit;
use Spryker\Zed\MerchantCommission\Communication\Plugin\MerchantCommission\PercentageMerchantCommissionCalculatorPlugin;
use Spryker\Zed\MerchantCommissionExtension\Communication\Dependency\Plugin\MerchantCommissionCalculatorPluginInterface;
use SprykerTest\Zed\MerchantCommission\MerchantCommissionCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantCommission
 * @group Communication
 * @group Plugin
 * @group MerchantCommission
 * @group PercentageMerchantCommissionCalculatorPluginTest
 * Add your own group annotations below this line
 */
class PercentageMerchantCommissionCalculatorPluginTest extends Unit
{
    /**
     * @uses \Spryker\Zed\Currency\CurrencyDependencyProvider::SERVICE_CURRENCY
     *
     * @var string
     */
    protected const SERVICE_CURRENCY = 'currency';

    /**
     * @uses \Spryker\Zed\MerchantCommission\Communication\Plugin\MerchantCommission\PercentageMerchantCommissionCalculatorPlugin::CALCULATOR_TYPE
     *
     * @var string
     */
    protected const CALCULATOR_TYPE = 'percentage';

    /**
     * @var \SprykerTest\Zed\MerchantCommission\MerchantCommissionCommunicationTester
     */
    protected MerchantCommissionCommunicationTester $tester;

    /**
     * @return void
     */
    public function testGetCalculatorTypeReturnsCorrectCalculatorType(): void
    {
        // Act
        $calculatorType = $this->createPercentageMerchantCommissionCalculatorPlugin()->getCalculatorType();

        // Assert
        $this->assertSame(static::CALCULATOR_TYPE, $calculatorType);
    }

    /**
     * @dataProvider transformAmountForPersistenceCorrectlyTransformsProvidedValueDataProvider
     *
     * @param float $value
     * @param int $transformedValue
     *
     * @return void
     */
    public function testTransformAmountForPersistenceCorrectlyTransformsProvidedValue(float $value, int $transformedValue): void
    {
        // Act
        $transformedAmount = $this->createPercentageMerchantCommissionCalculatorPlugin()->transformAmountForPersistence($value);

        // Assert
        $this->assertSame($transformedValue, $transformedAmount);
    }

    /**
     * @dataProvider transformAmountFromPersistenceCorrectlyTransformsProvidedValueDataProvider
     *
     * @param int $value
     * @param float $transformedValue
     *
     * @return void
     */
    public function testTransformAmountFromPersistenceCorrectlyTransformsProvidedValue(int $value, float $transformedValue): void
    {
        // Act
        $transformedAmount = $this->createPercentageMerchantCommissionCalculatorPlugin()->transformAmountFromPersistence($value);

        // Assert
        $this->assertSame($transformedValue, $transformedAmount);
    }

    /**
     * @dataProvider formatMerchantCommissionAmountCorrectlyFormatsProvidedValueDataProvider
     *
     * @param int $value
     * @param string $formattedValue
     *
     * @return void
     */
    public function testFormatMerchantCommissionAmountCorrectlyFormatsProvidedValue(int $value, string $formattedValue): void
    {
        // Act
        $formattedAmount = $this->createPercentageMerchantCommissionCalculatorPlugin()->formatMerchantCommissionAmount($value);

        // Assert
        $this->assertSame($formattedValue, $formattedAmount);
    }

    /**
     * @return list<list<float|int>>
     */
    protected function transformAmountForPersistenceCorrectlyTransformsProvidedValueDataProvider(): array
    {
        return [
            [10.5, 1050],
            [10.545, 1055],
            [10.0, 1000],
            [0.0, 0],
            [-10.0, -1000],
            [-10.5, -1050],
            [-10.545, -1055],
        ];
    }

    /**
     * @return list<list<float|int>>
     */
    protected function transformAmountFromPersistenceCorrectlyTransformsProvidedValueDataProvider(): array
    {
        return [
            [1050, 10.5],
            [1000, 10.0],
            [0, 0.0],
            [-1000, -10.0],
            [-1050, -10.5],
        ];
    }

    /**
     * @return array<array<int|string|null>>
     */
    protected function formatMerchantCommissionAmountCorrectlyFormatsProvidedValueDataProvider(): array
    {
        return [
            [1050, '10.5 %', null],
            [1000, '10 %', null],
            [0, '0 %', null],
            [-1000, '-10 %', null],
            [-1050, '-10.5 %', null],
        ];
    }

    /**
     * @return \Spryker\Zed\MerchantCommissionExtension\Communication\Dependency\Plugin\MerchantCommissionCalculatorPluginInterface
     */
    protected function createPercentageMerchantCommissionCalculatorPlugin(): MerchantCommissionCalculatorPluginInterface
    {
        return new PercentageMerchantCommissionCalculatorPlugin();
    }
}
