<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductPackagingUnit\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ProductPackagingUnitTypeBuilder;
use SprykerTest\Zed\ProductPackagingUnit\ProductPackagingUnitBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductPackagingUnit
 * @group Business
 * @group UpdateProductPackagingUnitTypeTest
 * Add your own group annotations below this line
 */
class UpdateProductPackagingUnitTypeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductPackagingUnit\ProductPackagingUnitBusinessTester
     */
    protected ProductPackagingUnitBusinessTester $tester;

    /**
     * @dataProvider getProductPackagingUnitTypeDataForNameChange
     *
     * @param string $name
     * @param string $newName
     *
     * @return void
     */
    public function testUpdateProductPackagingUnitTypeShouldUpdatePackagingUnitType(string $name, string $newName): void
    {
        // Arrange
        $productPackagingUnitTypeTransfer = (new ProductPackagingUnitTypeBuilder())
            ->build()
            ->setName($name);

        $productPackagingUnitTypeTransfer = $this->tester->getFacade()->createProductPackagingUnitType($productPackagingUnitTypeTransfer);

        // Act
        $productPackagingUnitTypeTransfer->setName($newName);
        $productPackagingUnitTypeTransfer = $this->tester->getFacade()->updateProductPackagingUnitType($productPackagingUnitTypeTransfer);

        // Assert
        $this->assertSame($productPackagingUnitTypeTransfer->getName(), $newName);
    }

    /**
     * @return array
     */
    protected function getProductPackagingUnitTypeDataForNameChange(): array
    {
        return [
            [
                'packaging_unit_type.test1.name',
                'packaging_unit_type.test2.name',
            ],
        ];
    }
}
