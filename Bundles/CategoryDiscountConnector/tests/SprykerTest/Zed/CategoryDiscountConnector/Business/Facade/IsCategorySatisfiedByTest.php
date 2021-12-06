<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CategoryDiscountConnector\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CategoryTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;

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
    protected $tester;

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
}
