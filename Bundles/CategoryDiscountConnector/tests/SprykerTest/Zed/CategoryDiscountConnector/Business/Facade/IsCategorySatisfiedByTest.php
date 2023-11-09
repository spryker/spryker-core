<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CategoryDiscountConnector\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\ProductCategoryCollectionTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use Spryker\Zed\CategoryDiscountConnector\CategoryDiscountConnectorDependencyProvider;
use Spryker\Zed\CategoryDiscountConnector\Dependency\Facade\CategoryDiscountConnectorToCategoryFacadeInterface;
use Spryker\Zed\CategoryDiscountConnector\Dependency\Facade\CategoryDiscountConnectorToProductCategoryFacadeInterface;
use SprykerTest\Zed\CategoryDiscountConnector\CategoryDiscountConnectorBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CategoryDiscountConnector
 * @group Business
 * @group Facade
 * @group IsCategorySatisfiedByTest
 * Add your own group annotations below this line
 */
class IsCategorySatisfiedByTest extends Unit
{
    /**
     * @var string
     */
    protected const CATEGORY_KEY = 'category-key';

    /**
     * @var string
     */
    protected const CATEGORY_KEY_2 = 'category-key-2';

    /**
     * @var string
     */
    protected const FAKE_CATEGORY_KEY = 'fake-category-key';

    /**
     * @see \Spryker\Zed\Discount\Business\QueryString\Comparator\IsIn::EXPRESSION
     *
     * @var string
     */
    protected const IS_IN_EXPRESSION = 'is in';

    /**
     * @see \Spryker\Zed\Discount\Business\QueryString\Comparator\IsNotIn::EXPRESSION
     *
     * @var string
     */
    protected const IS_NOT_IN_EXPRESSION = 'is not in';

    /**
     * @var \SprykerTest\Zed\CategoryDiscountConnector\CategoryDiscountConnectorBusinessTester
     */
    protected CategoryDiscountConnectorBusinessTester $tester;

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        $this->tester->cleanRuleCheckerStaticProperties();
    }

    /**
     * @return void
     */
    public function testIsCategorySatisfiedByChecksInRealCategoryKey(): void
    {
        // Arrange
        $categoryTransfer = $this->tester->haveLocalizedCategory([CategoryTransfer::CATEGORY_KEY => static::CATEGORY_KEY]);
        $quoteTransfer = $this->tester->createQuoteTransfer($categoryTransfer);

        // Act
        $isCategorySatisfied = $this->tester
            ->getFacade()
            ->isCategorySatisfiedBy(
                $quoteTransfer,
                $quoteTransfer->getItems()->getIterator()->current(),
                $this->tester->createClauseTransfer(static::IS_IN_EXPRESSION, [$categoryTransfer]),
            );

        // Assert
        $this->assertTrue($isCategorySatisfied);
    }

    /**
     * @return void
     */
    public function testIsCategorySatisfiedByChecksNotInRealCategoryKey(): void
    {
        // Arrange
        $categoryTransfer = $this->tester->haveLocalizedCategory([CategoryTransfer::CATEGORY_KEY => static::CATEGORY_KEY]);
        $quoteTransfer = $this->tester->createQuoteTransfer($categoryTransfer);

        // Act
        $isCategorySatisfied = $this->tester
            ->getFacade()
            ->isCategorySatisfiedBy(
                $quoteTransfer,
                $quoteTransfer->getItems()->getIterator()->current(),
                $this->tester->createClauseTransfer(static::IS_NOT_IN_EXPRESSION, [$categoryTransfer]),
            );

        // Assert
        $this->assertFalse($isCategorySatisfied);
    }

    /**
     * @return void
     */
    public function testIsCategorySatisfiedByChecksInFakeCategoryKey(): void
    {
        // Arrange
        $categoryTransfer = $this->tester->haveLocalizedCategory([CategoryTransfer::CATEGORY_KEY => static::CATEGORY_KEY]);
        $quoteTransfer = $this->tester->createQuoteTransfer($categoryTransfer);

        $categoryTransfer->setCategoryKey(static::FAKE_CATEGORY_KEY);

        // Act
        $isCategorySatisfied = $this->tester
            ->getFacade()
            ->isCategorySatisfiedBy(
                $quoteTransfer,
                $quoteTransfer->getItems()->getIterator()->current(),
                $this->tester->createClauseTransfer(static::IS_IN_EXPRESSION, [$categoryTransfer]),
            );

        // Assert
        $this->assertFalse($isCategorySatisfied);
    }

    /**
     * @return void
     */
    public function testIsCategorySatisfiedByChecksNotInFakeCategoryKey(): void
    {
        // Arrange
        $categoryTransfer = $this->tester->haveLocalizedCategory([CategoryTransfer::CATEGORY_KEY => static::CATEGORY_KEY]);
        $quoteTransfer = $this->tester->createQuoteTransfer($categoryTransfer);

        $categoryTransfer->setCategoryKey(static::FAKE_CATEGORY_KEY);

        // Act
        $isCategorySatisfied = $this->tester
            ->getFacade()
            ->isCategorySatisfiedBy(
                $quoteTransfer,
                $quoteTransfer->getItems()->getIterator()->current(),
                $this->tester->createClauseTransfer(static::IS_NOT_IN_EXPRESSION, [$categoryTransfer]),
            );

        // Assert
        $this->assertTrue($isCategorySatisfied);
    }

    /**
     * @return void
     */
    public function testGetDiscountableItemsByCategoryChecksQuoteWithoutClauseValue(): void
    {
        // Arrange
        $categoryTransfer = $this->tester->haveLocalizedCategory([CategoryTransfer::CATEGORY_KEY => static::CATEGORY_KEY]);
        $quoteTransfer = $this->tester->createQuoteTransfer($categoryTransfer);

        $clauseTransfer = $this->tester->createClauseTransfer(static::IS_IN_EXPRESSION, [$categoryTransfer]);
        $clauseTransfer->setValue(null);

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->tester
            ->getFacade()
            ->isCategorySatisfiedBy(
                $quoteTransfer,
                $quoteTransfer->getItems()->getIterator()->current(),
                $clauseTransfer,
            );
    }

    /**
     * @return void
     */
    public function testGetDiscountableItemsByCategoryChecksQuoteWithoutIdProductAbstract(): void
    {
        // Arrange
        $categoryTransfer = $this->tester->haveLocalizedCategory([CategoryTransfer::CATEGORY_KEY => static::CATEGORY_KEY]);
        $quoteTransfer = $this->tester->createQuoteTransfer($categoryTransfer);
        $quoteTransfer->getItems()->getIterator()->current()->setIdProductAbstract(null);

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->tester
            ->getFacade()
            ->isCategorySatisfiedBy(
                $quoteTransfer,
                $quoteTransfer->getItems()->getIterator()->current(),
                $this->tester->createClauseTransfer(static::IS_IN_EXPRESSION, [$categoryTransfer]),
            );
    }

    /**
     * @return void
     */
    public function testShouldNotCacheProductCategoriesWhenDifferentProductsAreVerified(): void
    {
        // Arrange
        $categoryTransfer = $this->tester->haveLocalizedCategory([CategoryTransfer::CATEGORY_KEY => static::CATEGORY_KEY]);
        $quoteTransfer = $this->tester->createQuoteTransfer($categoryTransfer);

        $categoryTransfer2 = $this->tester->haveLocalizedCategory([CategoryTransfer::CATEGORY_KEY => static::CATEGORY_KEY_2]);
        $quoteTransfer2 = $this->tester->createQuoteTransfer($categoryTransfer2);

        // Act
        $isCategorySatisfied = $this->tester->getFacade()->isCategorySatisfiedBy(
            $quoteTransfer,
            $quoteTransfer->getItems()->getIterator()->current(),
            $this->tester->createClauseTransfer(static::IS_IN_EXPRESSION, [$categoryTransfer]),
        );

        $isCategorySatisfied2 = $this->tester->getFacade()->isCategorySatisfiedBy(
            $quoteTransfer2,
            $quoteTransfer2->getItems()->getIterator()->current(),
            $this->tester->createClauseTransfer(static::IS_IN_EXPRESSION, [$categoryTransfer2]),
        );

        // Assert
        $this->assertTrue($isCategorySatisfied);
        $this->assertTrue($isCategorySatisfied2);
    }

    /**
     * @return void
     */
    public function testShouldCacheProductCategoriesWhenTheSameProductIsVerified(): void
    {
        // Arrange
        $categoryTransfer = $this->tester->haveLocalizedCategory([CategoryTransfer::CATEGORY_KEY => static::CATEGORY_KEY]);
        $quoteTransfer = $this->tester->createQuoteTransfer($categoryTransfer);

        $productCategoryFacadeMock = $this->createProductCategoryFacadeMock($this->tester->createProductCategoryCollectionTransfer($categoryTransfer, $quoteTransfer));
        $this->tester->setDependency(CategoryDiscountConnectorDependencyProvider::FACADE_PRODUCT_CATEGORY, $productCategoryFacadeMock);

        $categoryFacadeMock = $this->createCategoryFacadeMock($categoryTransfer);
        $this->tester->setDependency(CategoryDiscountConnectorDependencyProvider::FACADE_CATEGORY, $categoryFacadeMock);

        // Assert
        $productCategoryFacadeMock->expects($this->once())->method('getProductCategoryCollection');
        $categoryFacadeMock->expects($this->once())->method('getAscendantCategoryKeysGroupedByIdCategoryNode');

        // Act
        $this->tester->getFacade()->isCategorySatisfiedBy(
            $quoteTransfer,
            $quoteTransfer->getItems()->getIterator()->current(),
            $this->tester->createClauseTransfer(static::IS_IN_EXPRESSION, [$categoryTransfer]),
        );

        $this->tester->getFacade()->isCategorySatisfiedBy(
            $quoteTransfer,
            $quoteTransfer->getItems()->getIterator()->current(),
            $this->tester->createClauseTransfer(static::IS_IN_EXPRESSION, [$categoryTransfer]),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductCategoryCollectionTransfer $productCategoryCollectionTransfer
     *
     * @return \Spryker\Zed\CategoryDiscountConnector\Dependency\Facade\CategoryDiscountConnectorToProductCategoryFacadeInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createProductCategoryFacadeMock(
        ProductCategoryCollectionTransfer $productCategoryCollectionTransfer
    ): CategoryDiscountConnectorToProductCategoryFacadeInterface {
        $productCategoryFacadeMock = $this->getMockBuilder(CategoryDiscountConnectorToProductCategoryFacadeInterface::class)
            ->getMock();

        $productCategoryFacadeMock->method('getProductCategoryCollection')->willReturn($productCategoryCollectionTransfer);

        return $productCategoryFacadeMock;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Spryker\Zed\CategoryDiscountConnector\Dependency\Facade\CategoryDiscountConnectorToCategoryFacadeInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createCategoryFacadeMock(CategoryTransfer $categoryTransfer): CategoryDiscountConnectorToCategoryFacadeInterface
    {
        $categoryFacadeMock = $this->getMockBuilder(CategoryDiscountConnectorToCategoryFacadeInterface::class)
            ->getMock();

        $categoryFacadeMock->method('getAscendantCategoryKeysGroupedByIdCategoryNode')->willReturn([
            $categoryTransfer->getCategoryNode()->getIdCategoryNode() => [$categoryTransfer->getCategoryKey()],
        ]);

        return $categoryFacadeMock;
    }
}
