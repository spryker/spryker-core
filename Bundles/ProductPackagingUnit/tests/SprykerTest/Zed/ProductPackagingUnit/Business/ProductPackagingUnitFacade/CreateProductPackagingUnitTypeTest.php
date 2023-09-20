<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductPackagingUnit\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ProductPackagingUnitTypeBuilder;
use Generated\Shared\Transfer\ProductPackagingUnitTypeTranslationTransfer;
use Spryker\Zed\ProductPackagingUnit\Business\Exception\ProductPackagingUnitTypeUniqueViolationException;
use SprykerTest\Zed\ProductPackagingUnit\ProductPackagingUnitBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductPackagingUnit
 * @group Business
 * @group CreateProductPackagingUnitTypeTest
 * Add your own group annotations below this line
 */
class CreateProductPackagingUnitTypeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductPackagingUnit\ProductPackagingUnitBusinessTester
     */
    protected ProductPackagingUnitBusinessTester $tester;

    /**
     * @dataProvider getProductPackagingUnitTypeData
     *
     * @param string $name
     * @param \Generated\Shared\Transfer\ProductPackagingUnitTypeTranslationTransfer ...$nameTranslations
     *
     * @return void
     */
    public function testCreateProductPackagingUnitTypeShouldPersistPackagingUnitType(
        string $name,
        ProductPackagingUnitTypeTranslationTransfer ...$nameTranslations
    ): void {
        $productPackagingUnitTypeTransfer = (new ProductPackagingUnitTypeBuilder())
            ->build()
            ->setName($name);

        foreach ($nameTranslations as $nameTranslation) {
            $productPackagingUnitTypeTransfer->addProductPackagingUnitTypeTranslation($nameTranslation);
        }

        $this->tester->getFacade()->createProductPackagingUnitType($productPackagingUnitTypeTransfer);

        // Act
        $productPackagingUnitTypeTransfer = $this->tester->getFacade()->findProductPackagingUnitTypeByName($productPackagingUnitTypeTransfer);

        // Assert
        $this->assertNotNull($productPackagingUnitTypeTransfer->getIdProductPackagingUnitType());
        $this->assertCount($productPackagingUnitTypeTransfer->getTranslations()->count(), $nameTranslations);
    }

    /**
     * @dataProvider getProductPackagingUnitTypeData
     *
     * @param string $name
     * @param \Generated\Shared\Transfer\ProductPackagingUnitTypeTranslationTransfer ...$nameTranslations
     *
     * @return void
     */
    protected function testCreateProductPackagingUnitTypeShouldThrowExceptionIfDuplicateUnitTypeIsTryingToBeAdded(
        string $name,
        ProductPackagingUnitTypeTranslationTransfer ...$nameTranslations
    ): void {
        // Arrange
        $productPackagingUnitTypeTransfer = (new ProductPackagingUnitTypeBuilder())
            ->build()
            ->setName($name);

        foreach ($nameTranslations as $nameTranslation) {
            $productPackagingUnitTypeTransfer->addProductPackagingUnitTypeTranslation($nameTranslation);
        }

        $this->tester->getFacade()->createProductPackagingUnitType($productPackagingUnitTypeTransfer);

        // Assert
        $this->expectException(ProductPackagingUnitTypeUniqueViolationException::class);

        // Act
        $this->tester->getFacade()->createProductPackagingUnitType($productPackagingUnitTypeTransfer);
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
