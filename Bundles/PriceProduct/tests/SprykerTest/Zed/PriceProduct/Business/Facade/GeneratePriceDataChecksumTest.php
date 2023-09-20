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
 * @group GeneratePriceDataChecksumTest
 * Add your own group annotations below this line
 */
class GeneratePriceDataChecksumTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\PriceProduct\PriceProductBusinessTester
     */
    protected PriceProductBusinessTester $tester;

    /**
     * @return void
     */
    public function testGeneratePriceDataChecksum(): void
    {
        // Act
        $actualResult = $this->tester->getFacade()->generatePriceDataChecksum(['11', '22']);

        // Assert
        $this->assertSame('3b513d6f', $actualResult);
    }
}
