<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesDiscountConnector\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerTest\Zed\ProductOfferDiscountConnector\ProductOfferDiscountConnectorBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesDiscountConnector
 * @group Business
 * @group Facade
 * @group IsProductOfferReferenceSatisfiedByTest
 * Add your own group annotations below this line
 */
class IsProductOfferReferenceSatisfiedByTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_PRODUCT_OFFER_REFERENCE = 'test_product_offer_reference';

    /**
     * @uses \Spryker\Zed\Discount\Business\QueryString\Comparator\Equal::EXPRESSION
     *
     * @var string
     */
    protected const EXPRESSION_EQUAL = '=';

    /**
     * @var \SprykerTest\Zed\ProductOfferDiscountConnector\ProductOfferDiscountConnectorBusinessTester
     */
    protected ProductOfferDiscountConnectorBusinessTester $tester;

    /**
     * @return void
     */
    public function testReturnsTrueWhenProductOfferReferenceMatchesTheClause(): void
    {
        // Act
        $isProductOfferReferenceSatisfiedBy = $this->tester
            ->getFacade()
            ->isProductOfferReferenceSatisfiedBy(
                new QuoteTransfer(),
                (new ItemTransfer())->setProductOfferReference(static::TEST_PRODUCT_OFFER_REFERENCE),
                $this->tester->createClauseTransfer(static::EXPRESSION_EQUAL, static::TEST_PRODUCT_OFFER_REFERENCE),
            );

        // Assert
        $this->assertTrue($isProductOfferReferenceSatisfiedBy);
    }

    /**
     * @return void
     */
    public function testReturnsFalseWhenProductOfferReferenceDoesNotMatchTheClause(): void
    {
        // Act
        $isProductOfferReferenceSatisfiedBy = $this->tester
            ->getFacade()
            ->isProductOfferReferenceSatisfiedBy(
                new QuoteTransfer(),
                (new ItemTransfer())->setProductOfferReference('another_product_offer_reference'),
                $this->tester->createClauseTransfer(static::EXPRESSION_EQUAL, static::TEST_PRODUCT_OFFER_REFERENCE),
            );

        // Assert
        $this->assertFalse($isProductOfferReferenceSatisfiedBy);
    }

    /**
     * @return void
     */
    public function testReturnsFalseWhenProductOfferReferenceIsNotSetToItem(): void
    {
        // Act
        $isProductOfferReferenceSatisfiedBy = $this->tester
            ->getFacade()
            ->isProductOfferReferenceSatisfiedBy(
                new QuoteTransfer(),
                new ItemTransfer(),
                $this->tester->createClauseTransfer(static::EXPRESSION_EQUAL, static::TEST_PRODUCT_OFFER_REFERENCE),
            );

        // Assert
        $this->assertFalse($isProductOfferReferenceSatisfiedBy);
    }
}
