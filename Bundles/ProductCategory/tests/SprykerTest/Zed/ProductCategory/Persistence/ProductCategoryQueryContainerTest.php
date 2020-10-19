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
    protected const TESTING_LOCALE_NAME = 'en_US';
    protected const PRODUCT_ABSTRACT_NAME = 'PRODUCT_ABSTRACT';
    protected const PRODUCT_ABSTRACT_NAME_1 = 'PRODUCT_ABSTRACT_1';
    protected const PRODUCT_ABSTRACT_NAME_2 = 'PRODUCT_ABSTRACT_2';

    /**
     * @var \SprykerTest\Zed\ProductCategory\ProductCategoryPersistenceTester
     */
    protected $tester;

    /**
     * @dataProvider getQueryProductsAbstractBySearchTermData
     *
     * @param string[] $names
     * @param string $term
     * @param int $expectedCount
     *
     * @return void
     */
    public function testQueryProductsAbstractBySearchTerm(array $names, string $term, int $expectedCount): void
    {
        // Arrange
        $localeEntity = $this->tester->createLocaleEntity(static::TESTING_LOCALE_NAME);
        $localeTransfer = (new LocaleTransfer())->fromArray($localeEntity->toArray());

        foreach ($names as $name) {
            $this->tester->createProductAbstractEntity($name, $localeEntity);
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
            [[static::PRODUCT_ABSTRACT_NAME_1, static::PRODUCT_ABSTRACT_NAME_2], static::PRODUCT_ABSTRACT_NAME_1, 1],
            [[static::PRODUCT_ABSTRACT_NAME_1, static::PRODUCT_ABSTRACT_NAME_2], static::PRODUCT_ABSTRACT_NAME, 2],
            [[static::PRODUCT_ABSTRACT_NAME_1], static::PRODUCT_ABSTRACT_NAME_2, 0],
        ];
    }
}
