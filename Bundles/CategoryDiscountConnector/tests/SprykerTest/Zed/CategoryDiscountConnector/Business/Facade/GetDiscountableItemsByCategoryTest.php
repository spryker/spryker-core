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
 * @group GetDiscountableItemsByCategoryTest
 * Add your own group annotations below this line
 */
class GetDiscountableItemsByCategoryTest extends Unit
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
     * @see \Spryker\Shared\Price\PriceConfig::PRICE_MODE_NET
     *
     * @var string
     */
    protected const PRICE_NET_MODE = 'NET_MODE';

    /**
     * @uses \Spryker\Shared\Price\PriceConfig::PRICE_MODE_GROSS
     *
     * @var string
     */
    protected const GROSS_MODE = 'GROSS_MODE';

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
    public function testGetDiscountableItemsByCategoryChecksInRealCategoryKey(): void
    {
        // Arrange
        $categoryTransfer = $this->tester->haveLocalizedCategory([CategoryTransfer::CATEGORY_KEY => static::CATEGORY_KEY]);
        $quoteTransfer = $this->tester->createQuoteTransfer($categoryTransfer);
        $quoteTransfer->setPriceMode(static::GROSS_MODE);

        // Act
        $discountableItemTransfers = $this->tester
            ->getFacade()
            ->getDiscountableItemsByCategory(
                $quoteTransfer,
                $this->tester->createClauseTransfer(static::IS_IN_EXPRESSION, [$categoryTransfer]),
            );

        // Assert
        $this->assertCount(2, $discountableItemTransfers);

        $this->assertSame($quoteTransfer->getItems()->offsetGet(0)->getUnitGrossPrice(), $discountableItemTransfers[0]->getUnitPrice());
        $this->assertEquals($quoteTransfer->getItems()->offsetGet(0)->getCalculatedDiscounts(), $discountableItemTransfers[0]->getOriginalItemCalculatedDiscounts());
        $this->assertEquals($quoteTransfer->getItems()->offsetGet(0), $discountableItemTransfers[0]->getOriginalItem());
    }

    /**
     * @return void
     */
    public function testGetDiscountableItemsByCategoryChecksIsNotInOperator(): void
    {
        // Arrange
        $categoryTransfer = $this->tester->haveLocalizedCategory([CategoryTransfer::CATEGORY_KEY => static::CATEGORY_KEY]);
        $quoteTransfer = $this->tester->createQuoteTransfer($categoryTransfer);
        $quoteTransfer->setPriceMode(static::GROSS_MODE);

        // Act
        $discountableItemTransfers = $this->tester
            ->getFacade()
            ->getDiscountableItemsByCategory(
                $quoteTransfer,
                $this->tester->createClauseTransfer(static::IS_NOT_IN_EXPRESSION, [$categoryTransfer]),
            );

        // Assert
        $this->assertEmpty($discountableItemTransfers);
    }

    /**
     * @return void
     */
    public function testGetDiscountableItemsByCategoryChecksInFakeCategoryKey(): void
    {
        // Arrange
        $categoryTransfer = $this->tester->haveLocalizedCategory([CategoryTransfer::CATEGORY_KEY => static::CATEGORY_KEY]);
        $quoteTransfer = $this->tester->createQuoteTransfer($categoryTransfer);
        $quoteTransfer->setPriceMode(static::GROSS_MODE);

        $categoryTransfer->setCategoryKey(static::FAKE_CATEGORY_KEY);

        // Act
        $discountableItemTransfers = $this->tester
            ->getFacade()
            ->getDiscountableItemsByCategory(
                $quoteTransfer,
                $this->tester->createClauseTransfer(static::IS_IN_EXPRESSION, [$categoryTransfer]),
            );

        // Assert
        $this->assertEmpty($discountableItemTransfers);
    }

    /**
     * @return void
     */
    public function testGetDiscountableItemsByCategoryChecksQuoteWithoutPriceMode(): void
    {
        // Arrange
        $categoryTransfer = $this->tester->haveLocalizedCategory([CategoryTransfer::CATEGORY_KEY => static::CATEGORY_KEY]);
        $quoteTransfer = $this->tester->createQuoteTransfer($categoryTransfer);
        $quoteTransfer->setPriceMode(null);

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->tester
            ->getFacade()
            ->getDiscountableItemsByCategory(
                $quoteTransfer,
                $this->tester->createClauseTransfer(static::IS_IN_EXPRESSION, [$categoryTransfer]),
            );
    }

    /**
     * @return void
     */
    public function testGetDiscountableItemsByCategoryChecksQuoteWithoutUnitNetPrice(): void
    {
        // Arrange
        $categoryTransfer = $this->tester->haveLocalizedCategory([CategoryTransfer::CATEGORY_KEY => static::CATEGORY_KEY]);
        $quoteTransfer = $this->tester->createQuoteTransfer($categoryTransfer);
        $quoteTransfer->setPriceMode(static::PRICE_NET_MODE);

        $quoteTransfer->getItems()->getIterator()->current()->setUnitNetPrice(null);

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->tester
            ->getFacade()
            ->getDiscountableItemsByCategory(
                $quoteTransfer,
                $this->tester->createClauseTransfer(static::IS_IN_EXPRESSION, [$categoryTransfer]),
            );
    }

    /**
     * @return void
     */
    public function testGetDiscountableItemsByCategoryChecksQuoteWithoutUnitGrossPrice(): void
    {
        // Arrange
        $categoryTransfer = $this->tester->haveLocalizedCategory([CategoryTransfer::CATEGORY_KEY => static::CATEGORY_KEY]);
        $quoteTransfer = $this->tester->createQuoteTransfer($categoryTransfer);
        $quoteTransfer->setPriceMode(static::GROSS_MODE);

        $quoteTransfer->getItems()->getIterator()->current()->setUnitGrossPrice(null);

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->tester
            ->getFacade()
            ->getDiscountableItemsByCategory(
                $quoteTransfer,
                $this->tester->createClauseTransfer(static::IS_IN_EXPRESSION, [$categoryTransfer]),
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
        $quoteTransfer->setPriceMode(static::GROSS_MODE);

        $quoteTransfer->getItems()->getIterator()->current()->setIdProductAbstract(null);

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->tester
            ->getFacade()
            ->getDiscountableItemsByCategory(
                $quoteTransfer,
                $this->tester->createClauseTransfer(static::IS_IN_EXPRESSION, [$categoryTransfer]),
            );
    }

    /**
     * @return void
     */
    public function testGetDiscountableItemsByCategoryChecksQuoteWithoutClauseValue(): void
    {
        // Arrange
        $categoryTransfer = $this->tester->haveLocalizedCategory([CategoryTransfer::CATEGORY_KEY => static::CATEGORY_KEY]);
        $quoteTransfer = $this->tester->createQuoteTransfer($categoryTransfer);
        $quoteTransfer->setPriceMode(static::GROSS_MODE);

        $clauseTransfer = $this->tester->createClauseTransfer(static::IS_IN_EXPRESSION, [$categoryTransfer]);
        $clauseTransfer->setValue(null);

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->tester
            ->getFacade()
            ->getDiscountableItemsByCategory($quoteTransfer, $clauseTransfer);
    }
}
