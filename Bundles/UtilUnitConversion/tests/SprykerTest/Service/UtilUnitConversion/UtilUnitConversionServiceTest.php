<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\UtilUnitConversion;

use Codeception\Test\Unit;
use Spryker\Service\UtilUnitConversion\Exception\InvalidMeasurementUnitExchangeException;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Service
 * @group UtilUnitConversion
 * @group UtilUnitConversionServiceTest
 * Add your own group annotations below this line
 */
class UtilUnitConversionServiceTest extends Unit
{
    /**
     * @var \SprykerTest\Service\UtilUnitConversion\UtilUnitConversionServiceTester
     */
    protected $tester;

    /**
     * @var \Spryker\Service\UtilUnitConversion\UtilUnitConversionServiceInterface
     */
    protected $utilUnitConversionService;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->utilUnitConversionService = $this->tester->getLocator()->utilUnitConversion()->service();
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
        $actualResult = $this->utilUnitConversionService->getMeasurementUnitExchangeRatio($fromCode, $toCode);

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
        $this->utilUnitConversionService->getMeasurementUnitExchangeRatio($unknownCode, $unknownCode);
    }
}
