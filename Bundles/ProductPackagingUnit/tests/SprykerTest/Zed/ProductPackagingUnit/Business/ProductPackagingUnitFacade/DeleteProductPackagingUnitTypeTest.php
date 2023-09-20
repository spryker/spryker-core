<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductPackagingUnit\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ProductPackagingUnitTypeBuilder;
use Generated\Shared\Transfer\ProductPackagingUnitTypeTranslationTransfer;
use Spryker\Zed\ProductPackagingUnit\Business\Exception\ProductPackagingUnitTypeNotFoundException;
use SprykerTest\Zed\ProductPackagingUnit\ProductPackagingUnitBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductPackagingUnit
 * @group Business
 * @group DeleteProductPackagingUnitTypeTest
 * Add your own group annotations below this line
 */
class DeleteProductPackagingUnitTypeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductPackagingUnit\ProductPackagingUnitBusinessTester
     */
    protected ProductPackagingUnitBusinessTester $tester;

    /**
     * @dataProvider getProductPackagingUnitTypeData
     *
     * @param string $name
     *
     * @return void
     */
    public function testDeleteProductPackagingUnitTypeShouldDeletePackagingUnitType(string $name): void
    {
        // Arrange
        $productPackagingUnitTypeTransfer = (new ProductPackagingUnitTypeBuilder())
            ->build()
            ->setName($name);

        $this->tester->getFacade()->createProductPackagingUnitType($productPackagingUnitTypeTransfer);

        // Act
        $productPackagingUnitTypeDeleted = $this->tester->getFacade()->deleteProductPackagingUnitType($productPackagingUnitTypeTransfer);

        // Assert
        $this->assertTrue($productPackagingUnitTypeDeleted);

        $this->expectException(ProductPackagingUnitTypeNotFoundException::class);

        // Assert exception thrown
        $this->tester->getFacade()->getProductPackagingUnitTypeById($productPackagingUnitTypeTransfer);
    }

    /**
     * @return array
     */
    protected function getProductPackagingUnitTypeData(): array
    {
        return [
            [
                'packaging_unit_type.test1.name',
                (new ProductPackagingUnitTypeTranslationTransfer())
                    ->setLocaleCode('en_US')
                    ->setName('name1'),
                (new ProductPackagingUnitTypeTranslationTransfer())
                    ->setLocaleCode('de_DE')
                    ->setName('Name1'),
            ],
        ];
    }
}
