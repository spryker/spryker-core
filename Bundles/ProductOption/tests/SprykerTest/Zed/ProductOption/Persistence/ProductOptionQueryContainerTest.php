<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOption\Persistence;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\LocalizedAttributesBuilder;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Orm\Zed\ProductOption\Persistence\SpyProductAbstractProductOptionGroupQuery;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionGroupQuery;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionValueQuery;
use Spryker\Zed\ProductOption\Persistence\ProductOptionPersistenceFactory;
use Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductOption
 * @group Persistence
 * @group ProductOptionQueryContainerTest
 * Add your own group annotations below this line
 */
class ProductOptionQueryContainerTest extends Unit
{
    protected const TEST_LOCALE_NAME = 'xxx';
    protected const TEST_LOCALIZED_PRODUCT_NAME = 'Test Product';

    /**
     * @var \SprykerTest\Zed\ProductOption\ProductOptionPersistenceTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testQueryAllProductAbstractProductOptionGroupsReturnsCorrectQuery(): void
    {
        $productOptionQueryContainer = new ProductOptionQueryContainer();
        $productOptionQueryContainer->setFactory(new ProductOptionPersistenceFactory());
        $query = $productOptionQueryContainer->queryAllProductAbstractProductOptionGroups();

        $this->assertInstanceOf(SpyProductAbstractProductOptionGroupQuery::class, $query);
    }

    /**
     * @return void
     */
    public function testQueryAllProductOptionGroupsReturnsCorrectQuery(): void
    {
        $productOptionQueryContainer = new ProductOptionQueryContainer();
        $productOptionQueryContainer->setFactory(new ProductOptionPersistenceFactory());
        $query = $productOptionQueryContainer->queryAllProductOptionGroups();

        $this->assertInstanceOf(SpyProductOptionGroupQuery::class, $query);
    }

    /**
     * @return void
     */
    public function testQueryAllProductOptionValuesReturnsCorrectQuery(): void
    {
        $productOptionQueryContainer = new ProductOptionQueryContainer();
        $productOptionQueryContainer->setFactory(new ProductOptionPersistenceFactory());
        $query = $productOptionQueryContainer->queryAllProductOptionValues();

        $this->assertInstanceOf(SpyProductOptionValueQuery::class, $query);
    }

    public function testQueryProductsAbstractBySearchTermReturnsCorrectData(): void
    {
        // Arrange
        $localeTransfer = $this->tester->haveLocale([
            LocaleTransfer::LOCALE_NAME => static::TEST_LOCALE_NAME,
        ]);

        $localizedAttributesTransfer = (new LocalizedAttributesBuilder([
            LocalizedAttributesTransfer::LOCALE => $localeTransfer,
            LocalizedAttributesTransfer::NAME => static::TEST_LOCALIZED_PRODUCT_NAME
        ]))->build();
        $productAbstractTransfer1 = $this->tester->haveProductAbstract([
            ProductAbstractTransfer::LOCALIZED_ATTRIBUTES => [$localizedAttributesTransfer->toArray()],
        ]);
        $productAbstractTransfer2 = $this->tester->haveProductAbstract([
            ProductAbstractTransfer::LOCALIZED_ATTRIBUTES => [$localizedAttributesTransfer->toArray()],
        ]);
        $productAbstractIds = [
            $productAbstractTransfer1->getIdProductAbstract(),
            $productAbstractTransfer2->getIdProductAbstract(),
        ];

        $productOptionGroupTransfer = $this->tester->haveProductOptionGroup();

        // Act
        $productOptionQueryContainer = new ProductOptionQueryContainer();
        $productOptionQueryContainer->setFactory(new ProductOptionPersistenceFactory());
        $result = $productOptionQueryContainer->queryProductsAbstractBySearchTermForAssignment(
            static::TEST_LOCALIZED_PRODUCT_NAME,
            $productOptionGroupTransfer->getIdProductOptionGroup(),
            $localeTransfer
        )->find();

        // Assert
        $this->assertCount(2, $result);
        $this->assertContains($result->offsetGet(0)->getIdProductAbstract(), $productAbstractIds);
        $this->assertContains($result->offsetGet(1)->getIdProductAbstract(), $productAbstractIds);
    }
}
