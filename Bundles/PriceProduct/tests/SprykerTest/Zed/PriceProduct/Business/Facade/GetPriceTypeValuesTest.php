<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProduct\Business\Facade;

use Codeception\Test\Unit;
use SprykerTest\Zed\PriceProduct\PriceProductBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PriceProduct
 * @group Business
 * @group Facade
 * @group GetPriceTypeValuesTest
 * Add your own group annotations below this line
 */
class GetPriceTypeValuesTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\PriceProduct\PriceProductBusinessTester
     */
    protected PriceProductBusinessTester $tester;

    /**
     * @return void
     */
    public function testGetPriceTypeValuesShouldReturnListOfAllPersistedPriceTypes(): void
    {
        // Arrange
        $priceProductFacade = $this->tester->getFacade();

        $priceTypesBefore = $priceProductFacade->getPriceTypeValues();
        $priceProductFacade->createPriceType('test');

        // Act
        $priceTypesAfter = $priceProductFacade->getPriceTypeValues();

        // Assert
        $this->assertCount(count($priceTypesBefore) + 1, $priceTypesAfter);
    }
}
