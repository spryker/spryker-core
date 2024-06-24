<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantCommission\Communication\Plugin\MerchantCommission;

use Codeception\Test\Unit;
use Spryker\Zed\MerchantCommission\Communication\Plugin\MerchantCommission\FixedMerchantCommissionCalculatorPlugin;
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
 * @group FixedMerchantCommissionCalculatorPluginTest
 * Add your own group annotations below this line
 */
class FixedMerchantCommissionCalculatorPluginTest extends Unit
{
    /**
     * @uses \Spryker\Zed\Currency\CurrencyDependencyProvider::SERVICE_CURRENCY
     *
     * @var string
     */
    protected const SERVICE_CURRENCY = 'currency';

    /**
     * @uses \Spryker\Zed\MerchantCommission\Communication\Plugin\MerchantCommission\FixedMerchantCommissionCalculatorPlugin::CALCULATOR_TYPE
     *
     * @var string
     */
    protected const CALCULATOR_TYPE = 'fixed';

    /**
     * @var string
     */
    protected const CURRENCY_EUR = 'EUR';

    /**
     * @var string
     */
    protected const CURRENCY_USD = 'USD';

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
        $calculatorType = $this->createFixedMerchantCommissionCalculatorPlugin()->getCalculatorType();

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
        $transformedAmount = $this->createFixedMerchantCommissionCalculatorPlugin()->transformAmountForPersistence($value);

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
        $transformedAmount = $this->createFixedMerchantCommissionCalculatorPlugin()->transformAmountFromPersistence($value);

        // Assert
        $this->assertSame($transformedValue, $transformedAmount);
    }

    /**
     * @dataProvider formatMerchantCommissionAmountCorrectlyFormatsProvidedValueDataProvider
     *
     * @param int $value
     * @param string $formattedValue
     * @param string|null $currencyIsoCode
     *
     * @return void
     */
    public function testFormatMerchantCommissionAmountCorrectlyFormatsProvidedValue(int $value, string $formattedValue, ?string $currencyIsoCode): void
    {
        // Arrange
        $this->tester->getContainer()->set(static::SERVICE_CURRENCY, static::CURRENCY_EUR);
        $this->tester->setStoreLocale();

        // Act
        $formattedAmount = $this->createFixedMerchantCommissionCalculatorPlugin()->formatMerchantCommissionAmount($value, $currencyIsoCode);

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
            [10.0, 1000],
            [0.0, 0],
            [-10.0, -1000],
            [-10.5, -1050],
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
            [1050, '€10.50', static::CURRENCY_EUR],
            [1000, '€10.00', static::CURRENCY_EUR],
            [0, '€0.00', static::CURRENCY_EUR],
            [-1000, '-€10.00', static::CURRENCY_EUR],
            [-1050, '-€10.50', static::CURRENCY_EUR],
            [1050, '$10.50', static::CURRENCY_USD],
            [1000, '$10.00', static::CURRENCY_USD],
            [0, '$0.00', static::CURRENCY_USD],
            [-1000, '-$10.00', static::CURRENCY_USD],
            [-1050, '-$10.50', static::CURRENCY_USD],
        ];
    }

    /**
     * @return \Spryker\Zed\MerchantCommissionExtension\Communication\Dependency\Plugin\MerchantCommissionCalculatorPluginInterface
     */
    protected function createFixedMerchantCommissionCalculatorPlugin(): MerchantCommissionCalculatorPluginInterface
    {
        return new FixedMerchantCommissionCalculatorPlugin();
    }
}
