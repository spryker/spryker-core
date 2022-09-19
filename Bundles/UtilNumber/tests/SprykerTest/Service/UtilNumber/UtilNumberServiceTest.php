<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\UtilNumber;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\NumberFormatFilterTransfer;
use Generated\Shared\Transfer\NumberFormatFloatRequestTransfer;
use Generated\Shared\Transfer\NumberFormatIntRequestTransfer;
use NumberFormatter;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Service
 * @group UtilNumber
 * @group UtilNumberServiceTest
 * Add your own group annotations below this line
 */
class UtilNumberServiceTest extends Unit
{
    /**
     * @var \SprykerTest\Service\UtilNumber\UtilNumberServiceTester
     */
    protected UtilNumberServiceTester $tester;

    /**
     * @dataProvider getFormatIntegerDataProvider
     *
     * @param string $expectedValue
     * @param int $testValue
     * @param string $locale
     * @param int|null $numberFormatStyle
     * @param int|null $maxFractionDigits
     *
     * @return void
     */
    public function testFormatIntegerCorrectly(
        string $expectedValue,
        int $testValue,
        string $locale,
        ?int $numberFormatStyle = null,
        ?int $maxFractionDigits = null
    ): void {
        // Arrange
        $numberFormatFilter = (new NumberFormatFilterTransfer())
            ->setLocale($locale)
            ->setNumberFormatStyle($numberFormatStyle)
            ->setMaxFractionDigits($maxFractionDigits);

        $numberFormatIntRequestTransfer = (new NumberFormatIntRequestTransfer())
            ->setNumber($testValue)
            ->setNumberFormatFilter($numberFormatFilter);

        // Act
        $actualValue = $this->tester->getService()->formatInt($numberFormatIntRequestTransfer);

        // Assert
        $this->assertSame($expectedValue, $actualValue);
    }

    /**
     * @dataProvider getFormatFloatDataProvider
     *
     * @param string $expectedValue
     * @param float $testValue
     * @param string $locale
     * @param int|null $numberFormatStyle
     * @param int|null $maxFractionDigits
     *
     * @return void
     */
    public function testFormatsFloatCorrectly(
        string $expectedValue,
        float $testValue,
        string $locale,
        ?int $numberFormatStyle = null,
        ?int $maxFractionDigits = null
    ): void {
        // Arrange
        $numberFormatFilter = (new NumberFormatFilterTransfer())
            ->setLocale($locale)
            ->setNumberFormatStyle($numberFormatStyle)
            ->setMaxFractionDigits($maxFractionDigits);

        $numberFormatFloatRequestTransfer = (new NumberFormatFloatRequestTransfer())
            ->setNumber($testValue)
            ->setNumberFormatFilter($numberFormatFilter);

        // Act
        $actualValue = $this->tester->getService()->formatFloat($numberFormatFloatRequestTransfer);

        // Assert
        $this->assertSame($expectedValue, $actualValue);
    }

    /**
     * @dataProvider getNumberFormatConfigWithDefaultFormatStyleDataProvider
     *
     * @param string $expectedGroupingSeparatorSymbol
     * @param string $expectedDecimalSeparatorSymbol
     * @param string|null $locale
     *
     * @return void
     */
    public function testGetNumberFormatConfigWithDefaultFormatStyle(
        string $expectedGroupingSeparatorSymbol,
        string $expectedDecimalSeparatorSymbol,
        ?string $locale = null
    ): void {
        // Act
        $numberFormatConfigTransfer = $this->tester->getService()->getNumberFormatConfig($locale);

        // Assert
        $this->assertSame($expectedGroupingSeparatorSymbol, $numberFormatConfigTransfer->getGroupingSeparatorSymbol());
        $this->assertSame($expectedDecimalSeparatorSymbol, $numberFormatConfigTransfer->getDecimalSeparatorSymbol());
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function getFormatIntegerDataProvider(): array
    {
        return [
            'Format int for de_DE locale with default format style' => [
                '123.456.789', 123456789, 'de_DE',
            ],
            'Format int for en_US locale with default format style' => [
                '123,456,789', 123456789, 'en_US',
            ],
            'Format int for en_US locale with configured format style' => [
                '12,345,678,900%', 123456789, 'en_US', NumberFormatter::PERCENT, 1,
            ],
        ];
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function getFormatFloatDataProvider(): array
    {
        return [
            'Format float for de_DE locale' => [
                '123.456,789', 123456.789, 'de_DE',
            ],
            'Format float for en_US locale' => [
                '123,456.789', 123456.789, 'en_US',
            ],
            'Format float for de_DE locale with configured format style' => [
                '1,234.56789', 1234.56789, 'en_US', null, 5,
            ],
            'Format float for en_US locale with configured format style' => [
                '123,456.79%', 1234.56789, 'en_US', NumberFormatter::PERCENT, 2,
            ],
        ];
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function getNumberFormatConfigWithDefaultFormatStyleDataProvider(): array
    {
        return [
            'Number format for de_DE locale' => [
                '.', ',', 'de_DE',
            ],
            'Number format for en_US locale' => [
                ',', '.', 'en_US',
            ],
            'Number format for default locale' => [
                ',', '.', null,
            ],
        ];
    }
}
