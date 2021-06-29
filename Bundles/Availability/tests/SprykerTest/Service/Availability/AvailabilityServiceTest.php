<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\Availability;

use Codeception\Test\Unit;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Service
 * @group Availability
 * @group AvailabilityServiceTest
 * Add your own group annotations below this line
 */
class AvailabilityServiceTest extends Unit
{
    /**
     * @var \SprykerTest\Service\Availability\AvailabilityServiceTester
     */
    protected $tester;

    /**
     * @dataProvider getIsAbstractProductNeverOutOfStockDataProvider
     *
     * @param string $productConcretesNeverOutOfStockSet
     * @param bool $expectedValue
     *
     * @return void
     */
    public function testIsAbstractProductNeverOutOfStockWillCheckIfSetContainsPositiveValue(
        string $productConcretesNeverOutOfStockSet,
        bool $expectedValue
    ): void {
        // Arrange
        $availabilityService = $this->tester->getService();

        // Act
        $isAbstractProductNeverOutOfStock = $availabilityService->isAbstractProductNeverOutOfStock($productConcretesNeverOutOfStockSet);

        // Assert
        $this->assertSame($expectedValue, $isAbstractProductNeverOutOfStock);
    }

    /**
     * @return array
     */
    public function getIsAbstractProductNeverOutOfStockDataProvider(): array
    {
        return [
            ['0,1,1,0,1', true],
            ['0,0,0', false],
            ['true,false,false', true],
            ['false,false', false],
        ];
    }
}
