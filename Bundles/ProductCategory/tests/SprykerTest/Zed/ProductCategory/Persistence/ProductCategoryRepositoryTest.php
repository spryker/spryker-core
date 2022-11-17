<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductCategory\Persistence;

use Codeception\Test\Unit;
use Propel\Runtime\Propel;
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
    /**
     * @var array<string>
     */
    protected const LOCALE_NAMES = ['en_US', 'de_DE'];

    /**
     * @var string
     */
    protected const PRODUCT_ABSTRACT_SKU = 'PRODUCT_ABSTRACT';

    /**
     * @var string
     */
    protected const CATEGORY_NAME_1 = 'CATEGORY_1';

    /**
     * @var string
     */
    protected const CATEGORY_NAME_2 = 'CATEGORY_2';

    /**
     * @var string
     */
    protected const CATEGORY_ATTRIBUTE_1 = 'CATEGORY_ATTRIBUTE_1';

    /**
     * @var string
     */
    protected const CATEGORY_ATTRIBUTE_2 = 'CATEGORY_ATTRIBUTE_2';

    /**
     * @var string
     */
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
        $localeEntity = $this->tester->createLocaleEntity(static::LOCALE_NAMES[0]);
        $productAbstract = $this->tester->createProductAbstractEntityWithCategories(
            static::PRODUCT_ABSTRACT_SKU,
            $categoriesData,
            $localeEntity,
        );

        $productCategoryQueryContainer = new ProductCategoryRepository();

        // Act
        $productCategoryTransferCollection = $productCategoryQueryContainer->getCategoryTransferCollectionByIdProductAbstract(
            $productAbstract->getIdProductAbstract(),
            $localeEntity->getIdLocale(),
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
                2,
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
                2,
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

    /**
     * @return void
     */
    public function testGetProductCategoryCollectionContainsNecessaryLocales(): void
    {
        // Arrange
        $productAbstract = $this->tester->createProductAbstractEntityWithCategoryWithLocalizedAttributes(
            static::PRODUCT_ABSTRACT_SKU,
            static::LOCALE_NAMES[0],
            static::LOCALE_NAMES,
        );

        $productCategoryCriteriaTransfer = $this->tester
            ->createProductCategoryCriteriaTransferBy(
                $productAbstract->getIdProductAbstract(),
            );

        $productCategoryRepository = new ProductCategoryRepository();

        // Act
        $productCategoryTransferCollection = $productCategoryRepository->getProductCategoryCollection(
            $productCategoryCriteriaTransfer,
        );

        // Assert
        $this->assertCount(1, $productCategoryTransferCollection->getProductCategories());

        $categoryTransfer = $productCategoryTransferCollection->getProductCategories()->offsetGet(0)->getCategory();

        $this->tester->assertCategoryContainsNecessaryLocales($categoryTransfer, static::LOCALE_NAMES);
    }

    /**
     * @return void
     */
    public function testUseSeveralRepositoryMethodsInOneRequestReturnDifferentResults(): void
    {
        // Arrange
        $productAbstract = $this->tester->createProductAbstractEntityWithCategoryWithLocalizedAttributes(
            static::PRODUCT_ABSTRACT_SKU,
            static::LOCALE_NAMES[0],
            static::LOCALE_NAMES,
        );

        $productCategoryCriteriaTransfer = $this->tester
            ->createProductCategoryCriteriaTransferBy(
                $productAbstract->getIdProductAbstract(),
            );

        $productCategoryRepository = new ProductCategoryRepository();

        // Act

        // It's necessary to have this code to populate the instance pool to emulate real conditions in what target
        // method runs
        // The $isPropelInstancePoolingEnabled is necessary to keep original Propel::isInstancePoolingEnabled state

        $isPropelInstancePoolingEnabled = Propel::isInstancePoolingEnabled();

        if (!$isPropelInstancePoolingEnabled) {
            Propel::enableInstancePooling();
        }

        $productCategoryRepository->getProductCategoryCollection(
            $productCategoryCriteriaTransfer,
        );

        $categoryCollectionTransfer = $productCategoryRepository
            ->getCategoryTransferCollectionByIdProductAbstract(
                $productAbstract->getIdProductAbstract(),
                $this->tester->createLocaleEntity(static::LOCALE_NAMES[0])->getIdLocale(),
            );

        // Disable PropelInstancePooling if it was not initially enabled
        if (!$isPropelInstancePoolingEnabled) {
            Propel::disableInstancePooling();
        }

        // Assert
        $this->assertCount(1, $categoryCollectionTransfer->getCategories());

        $categoryTransfer = $categoryCollectionTransfer->getCategories()->offsetGet(0);

        $this->tester->assertCategoryContainsNecessaryLocales($categoryTransfer, [static::LOCALE_NAMES[0]]);
    }
}
