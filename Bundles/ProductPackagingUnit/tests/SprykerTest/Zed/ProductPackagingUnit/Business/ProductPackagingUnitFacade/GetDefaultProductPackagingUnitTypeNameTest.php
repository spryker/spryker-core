<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductPackagingUnit\Business;

use Codeception\Stub;
use Codeception\Test\Unit;
use Spryker\Zed\ProductPackagingUnit\ProductPackagingUnitConfig;
use SprykerTest\Zed\ProductPackagingUnit\ProductPackagingUnitBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductPackagingUnit
 * @group Business
 * @group GetDefaultProductPackagingUnitTypeNameTest
 * Add your own group annotations below this line
 */
class GetDefaultProductPackagingUnitTypeNameTest extends Unit
{
    /**
     * @var string
     */
    protected const DEFAULT_PRODUCT_PACKAGING_UNIT_TYPE_NAME = 'packaging_unit_type.item.name';

    /**
     * @var \SprykerTest\Zed\ProductPackagingUnit\ProductPackagingUnitBusinessTester
     */
    protected ProductPackagingUnitBusinessTester $tester;

    /**
     * @return void
     */
    public function testDefaultProductPackagingUnitTypeName(): void
    {
        // Arrange
        $configDefaultProductPackagingUnitTypMockName = $this->getConfigStub()->getDefaultProductPackagingUnitTypeName();

        // Act
        $defaultProductPackagingUnitTypeName = $this->tester->getFacade()->getDefaultProductPackagingUnitTypeName();

        // Assert
        $this->assertSame($configDefaultProductPackagingUnitTypMockName, $defaultProductPackagingUnitTypeName);
    }

    /**
     * @return \Spryker\Zed\ProductPackagingUnit\ProductPackagingUnitConfig|object
     */
    protected function getConfigStub()
    {
        return Stub::make(ProductPackagingUnitConfig::class, [
            'getDefaultProductPackagingUnitTypeName' => function () {
                return static::DEFAULT_PRODUCT_PACKAGING_UNIT_TYPE_NAME;
            },
        ]);
    }
}
