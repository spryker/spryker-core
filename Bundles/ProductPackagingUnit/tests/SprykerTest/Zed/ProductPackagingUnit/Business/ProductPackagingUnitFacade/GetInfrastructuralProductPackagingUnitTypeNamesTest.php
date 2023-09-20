<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductPackagingUnit\Business;

use Codeception\Test\Unit;
use SprykerTest\Zed\ProductPackagingUnit\ProductPackagingUnitBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductPackagingUnit
 * @group Business
 * @group GetInfrastructuralProductPackagingUnitTypeNamesTest
 * Add your own group annotations below this line
 */
class GetInfrastructuralProductPackagingUnitTypeNamesTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductPackagingUnit\ProductPackagingUnitBusinessTester
     */
    protected ProductPackagingUnitBusinessTester $tester;

    /**
     * @return void
     */
    public function testGetInfrastructuralProductPackagingUnitTypeNames(): void
    {
        // Act
        $infrastructuralProductPackagingUnitTypeNames = $this->tester->getFacade()->getInfrastructuralProductPackagingUnitTypeNames();

        // Assert
        $this->assertCount(1, $infrastructuralProductPackagingUnitTypeNames);
    }
}
