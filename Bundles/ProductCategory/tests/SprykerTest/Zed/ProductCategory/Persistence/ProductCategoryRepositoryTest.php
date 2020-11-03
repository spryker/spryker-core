<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductCategory\Persistence;

use Codeception\Test\Unit;
use Spryker\Zed\ProductCategory\Persistence\ProductCategoryRepository;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductCategory
 * @group Persistence
 * @group ProductCategoryRepositoryTest
 * Add your own group annotations below this line
 */
class ProductCategoryRepositoryTest extends Unit
{
    protected const LOCALE_NAME = 'en_US';

    protected const PRODUCT_ABSTRACT_SKU = 'PRODUCT_ABSTRACT';

    protected const CATEGORY_NAME_1 = 'CATEGORY_1';
    protected const CATEGORY_NAME_2 = 'CATEGORY_2';

    protected const CATEGORY_ATTRIBUTE_1 = 'CATEGORY_ATTRIBUTE_1';
    protected const CATEGORY_ATTRIBUTE_2 = 'CATEGORY_ATTRIBUTE_2';
    protected const CATEGORY_ATTRIBUTE_3 = 'CATEGORY_ATTRIBUTE_3';

    /**
     * @var \SprykerTest\Zed\ProductCategory\ProductCategoryPersistenceTester
     */
    protected $tester;

    /**
     * @dataProvider getCategoryTransferCollectionByIdProductAbstractData
     *
     * @param array $categoriesData
     * @param int $expectedCount
     *
     * @return void
     */
    public function testGetCategoryTransferCollectionByIdProductAbstractShouldReturnCategoryCollection(array $categoriesData, int $expectedCount): void
    {
        // Arrange
        $localeEntity = $this->tester->createLocaleEntity(static::LOCALE_NAME);
        $productAbstract = $this->tester->createProductAbstractEntityWithCategories(
            static::PRODUCT_ABSTRACT_SKU,
            $categoriesData,
            $localeEntity
        );

        $productCategoryQueryContainer = new ProductCategoryRepository();

        // Act
        $productCategoryTransferCollection = $productCategoryQueryContainer->getCategoryTransferCollectionByIdProductAbstract(
            $productAbstract->getIdProductAbstract(),
            $localeEntity->getIdLocale()
        );

        // Assert
        $this->assertSame($expectedCount, count($productCategoryTransferCollection->getCategories()));
    }

    /**
     * @return array
     */
    public function getCategoryTransferCollectionByIdProductAbstractData(): array
    {
        return [
            [
                [
                    static::CATEGORY_NAME_1 => [
                        static::CATEGORY_ATTRIBUTE_1,
                        static::CATEGORY_ATTRIBUTE_2,
                    ],
                    static::CATEGORY_NAME_2 => [
                        static::CATEGORY_ATTRIBUTE_3,
                    ],
                ],
                3,
            ],
            [
                [
                    static::CATEGORY_NAME_1 => [
                        static::CATEGORY_ATTRIBUTE_1,
                        static::CATEGORY_ATTRIBUTE_2,
                    ],
                    static::CATEGORY_NAME_2 => [
                        static::CATEGORY_ATTRIBUTE_2,
                    ],
                ],
                3,
            ],
            [
                [
                    static::CATEGORY_NAME_1 => [
                        static::CATEGORY_ATTRIBUTE_1,
                        static::CATEGORY_ATTRIBUTE_1,
                    ],
                ],
                1,
            ],
        ];
    }
}
