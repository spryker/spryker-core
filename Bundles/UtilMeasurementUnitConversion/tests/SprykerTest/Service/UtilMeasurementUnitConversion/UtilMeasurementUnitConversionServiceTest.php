<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\UtilMeasurementUnitConversion;

use Codeception\Test\Unit;
use Spryker\Service\UtilMeasurementUnitConversion\Exception\InvalidMeasurementUnitExchangeException;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Service
 * @group UtilMeasurementUnitConversion
 * @group UtilMeasurementUnitConversionServiceTest
 * Add your own group annotations below this line
 */
class UtilMeasurementUnitConversionServiceTest extends Unit
{
    /**
     * @var \SprykerTest\Service\UtilMeasurementUnitConversion\UtilMeasurementUnitConversionServiceTester
     */
    protected $tester;

    /**
     * @var \Spryker\Service\UtilMeasurementUnitConversion\UtilMeasurementUnitConversionServiceInterface
     */
    protected $utilMeasurementUnitConversionService;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->utilMeasurementUnitConversionService = $this->tester->getLocator()->utilMeasurementUnitConversion()->service();
    }

    /**
     * @dataProvider getExampleMeasurementUnitConversions
     *
     * @param string $fromCode
     * @param string $toCode
     * @param float $expectedResult
     *
     * @return void
     */
    public function testGetMeasurementUnitExchangeRatioRetrievesValue(string $fromCode, string $toCode, float $expectedResult): void
    {
        // Act
        $actualResult = $this->utilMeasurementUnitConversionService->getMeasurementUnitExchangeRatio($fromCode, $toCode);

        // Assert
        $this->assertSame($expectedResult, $actualResult);
    }

    /**
     * @return array
     */
    public function getExampleMeasurementUnitConversions(): array
    {
        return [
            ["METR", "CMET", 100],
            ["CMET", "METR", 0.01],
        ];
    }

    /**
     * @return void
     */
    public function testGetMeasurementUnitExchangeRatioThrowsExceptionOnUndefinedExchangeRequest(): void
    {
        // Assign
        $unknownCode = "UNKNOWN";

        // Assert
        $this->expectException(InvalidMeasurementUnitExchangeException::class);

        // Act
        $this->utilMeasurementUnitConversionService->getMeasurementUnitExchangeRatio($unknownCode, $unknownCode);
    }
}
