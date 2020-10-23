<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductCategory\Persistence;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\ProductCategory\Persistence\ProductCategoryQueryContainer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductCategory
 * @group Persistence
 * @group ProductCategoryQueryContainerTest
 * Add your own group annotations below this line
 */
class ProductCategoryQueryContainerTest extends Unit
{
    protected const LOCALE_NAME = 'en_US';
    protected const PRODUCT_ABSTRACT_SKU = 'PRODUCT_ABSTRACT';
    protected const PRODUCT_ATTRIBUTE_NAME = 'PRODUCT_ATTRIBUTE';

    protected const PRODUCT_ABSTRACT_SKU_1 = 'PRODUCT_ABSTRACT_1';
    protected const PRODUCT_ATTRIBUTE_NAME_1 = 'PRODUCT_ATTRIBUTE_1';

    protected const PRODUCT_ABSTRACT_SKU_2 = 'PRODUCT_ABSTRACT_2';
    protected const PRODUCT_ATTRIBUTE_NAME_2 = 'PRODUCT_ATTRIBUTE_2';

    /**
     * @var \SprykerTest\Zed\ProductCategory\ProductCategoryPersistenceTester
     */
    protected $tester;

    /**
     * @dataProvider getQueryProductsAbstractBySearchTermData
     *
     * @param array $productData
     * @param string $term
     * @param int $expectedCount
     *
     * @return void
     */
    public function testQueryProductsAbstractBySearchTermShouldReturnQueryWithProductsFilteredByTerm(
        array $productData,
        string $term,
        int $expectedCount
    ): void {
        // Arrange
        $localeEntity = $this->tester->createLocaleEntity(static::LOCALE_NAME);
        $localeTransfer = (new LocaleTransfer())->fromArray($localeEntity->toArray());

        foreach ($productData as $sku => $attributeName) {
            $this->tester->createProductAbstractEntity($sku, $attributeName, $localeEntity);
        }

        $productCategoryQueryContainer = new ProductCategoryQueryContainer();

        // Act
        $productCategoryQuery = $productCategoryQueryContainer->queryProductsAbstractBySearchTerm($term, $localeTransfer);

        // Assert
        $this->assertSame($expectedCount, $productCategoryQuery->count());
    }

    /**
     * @return array
     */
    public function getQueryProductsAbstractBySearchTermData(): array
    {
        return [
            [
                [
                    static::PRODUCT_ABSTRACT_SKU_1 => static::PRODUCT_ATTRIBUTE_NAME_1,
                    static::PRODUCT_ABSTRACT_SKU_2 => static::PRODUCT_ATTRIBUTE_NAME_2,
                ],
                static::PRODUCT_ABSTRACT_SKU_1,
                1,
            ],
            [
                [
                    static::PRODUCT_ABSTRACT_SKU_1 => static::PRODUCT_ATTRIBUTE_NAME_2,
                    static::PRODUCT_ABSTRACT_SKU_2 => static::PRODUCT_ATTRIBUTE_NAME_2,
                ],
                static::PRODUCT_ABSTRACT_SKU,
                2,
            ],
            [
                [
                    static::PRODUCT_ABSTRACT_SKU_1 => static::PRODUCT_ATTRIBUTE_NAME_2,
                ],
                static::PRODUCT_ABSTRACT_SKU_2,
                0,
            ],
            [
                [
                    static::PRODUCT_ABSTRACT_SKU_1 => static::PRODUCT_ATTRIBUTE_NAME_1,
                ],
                static::PRODUCT_ATTRIBUTE_NAME_1,
                1,
            ],
            [
                [
                    static::PRODUCT_ABSTRACT_SKU_1 => static::PRODUCT_ATTRIBUTE_NAME_1,
                    static::PRODUCT_ABSTRACT_SKU_2 => static::PRODUCT_ATTRIBUTE_NAME_2,
                ],
                static::PRODUCT_ATTRIBUTE_NAME,
                2,
            ],
        ];
    }
}
