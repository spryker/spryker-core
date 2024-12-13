<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesDiscountConnector\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CalculatedDiscountTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use SprykerTest\Zed\ProductOfferDiscountConnector\ProductOfferDiscountConnectorBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesDiscountConnector
 * @group Business
 * @group Facade
 * @group GetDiscountableItemsByProductOfferReferenceTest
 * Add your own group annotations below this line
 */
class GetDiscountableItemsByProductOfferReferenceTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_PRODUCT_OFFER_REFERENCE = 'test_product_offer_reference';

    /**
     * @uses \Spryker\Shared\Price\PriceConfig::PRICE_MODE_NET
     *
     * @var string
     */
    protected const PRICE_MODE_NET = 'NET_MODE';

    /**
     * @uses \Spryker\Shared\Price\PriceConfig::PRICE_MODE_GROSS
     *
     * @var string
     */
    protected const PRICE_MODE_GROSS = 'GROSS_MODE';

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
    public function testReturnsDiscountableItemsWithMatchedProductOfferReferenceOnly(): void
    {
        // Arrange
        $itemTransfer1 = (new ItemTransfer())->setIdSalesOrderItem(1)->setUnitNetPrice(100);
        $itemTransfer2 = (new ItemTransfer())->setIdSalesOrderItem(2)
            ->setProductOfferReference(static::TEST_PRODUCT_OFFER_REFERENCE)
            ->setUnitNetPrice(100);
        $itemTransfer3 = (new ItemTransfer())->setIdSalesOrderItem(3)
            ->setProductOfferReference('another_product_offer_reference')
            ->setUnitNetPrice(100);
        $quoteTransfer = (new QuoteTransfer())
            ->setPriceMode(static::PRICE_MODE_NET)
            ->addItem($itemTransfer1)
            ->addItem($itemTransfer2)
            ->addItem($itemTransfer3);
        $clauseTransfer = $this->tester->createClauseTransfer(static::EXPRESSION_EQUAL, static::TEST_PRODUCT_OFFER_REFERENCE);

        // Act
        $discountableItemTransfers = $this->tester
            ->getFacade()
            ->getDiscountableItemsByProductOfferReference($quoteTransfer, $clauseTransfer);

        // Assert
        $this->assertCount(1, $discountableItemTransfers);
        $this->assertSame(
            $itemTransfer2->getIdSalesOrderItem(),
            $discountableItemTransfers[0]->getOriginalItemOrFail()->getIdSalesOrderItem(),
        );
    }

    /**
     * @return void
     */
    public function testCorrectlyMapsItemTransferToDiscountableItemTransferWithNetPriceMode(): void
    {
        // Arrange
        $calculatedDiscountTransfer = (new CalculatedDiscountTransfer())->setIdDiscount(1);
        $itemTransfer = (new ItemTransfer())
            ->setIdSalesOrderItem(1)
            ->setProductOfferReference(static::TEST_PRODUCT_OFFER_REFERENCE)
            ->setUnitNetPrice(100)
            ->addCalculatedDiscount($calculatedDiscountTransfer);
        $quoteTransfer = (new QuoteTransfer())
            ->setPriceMode(static::PRICE_MODE_NET)
            ->addItem($itemTransfer);
        $clauseTransfer = $this->tester->createClauseTransfer(static::EXPRESSION_EQUAL, static::TEST_PRODUCT_OFFER_REFERENCE);

        // Act
        $discountableItemTransfers = $this->tester
            ->getFacade()
            ->getDiscountableItemsByProductOfferReference($quoteTransfer, $clauseTransfer);

        // Assert
        $this->assertDiscountableItemProperties($discountableItemTransfers, $itemTransfer, $calculatedDiscountTransfer, static::PRICE_MODE_NET);
    }

    /**
     * @return void
     */
    public function testCorrectlyMapsItemTransferToDiscountableItemTransferWithGrossPriceMode(): void
    {
        // Arrange
        $calculatedDiscountTransfer = (new CalculatedDiscountTransfer())->setIdDiscount(1);
        $itemTransfer = (new ItemTransfer())
            ->setIdSalesOrderItem(1)
            ->setProductOfferReference(static::TEST_PRODUCT_OFFER_REFERENCE)
            ->setUnitGrossPrice(100)
            ->addCalculatedDiscount($calculatedDiscountTransfer);
        $quoteTransfer = (new QuoteTransfer())
            ->setPriceMode(static::PRICE_MODE_GROSS)
            ->addItem($itemTransfer);
        $clauseTransfer = $this->tester->createClauseTransfer(static::EXPRESSION_EQUAL, static::TEST_PRODUCT_OFFER_REFERENCE);

        // Act
        $discountableItemTransfers = $this->tester
            ->getFacade()
            ->getDiscountableItemsByProductOfferReference($quoteTransfer, $clauseTransfer);

        // Assert
        $this->assertDiscountableItemProperties($discountableItemTransfers, $itemTransfer, $calculatedDiscountTransfer, static::PRICE_MODE_GROSS);
    }

    /**
     * @return void
     */
    public function testThrowsNulValueExceptionWhenQuutePriceModeIsNotSet(): void
    {
        // Arrange
        $itemTransfer = (new ItemTransfer())->setProductOfferReference(static::TEST_PRODUCT_OFFER_REFERENCE);
        $quoteTransfer = (new QuoteTransfer())->addItem($itemTransfer);
        $clauseTransfer = $this->tester->createClauseTransfer(static::EXPRESSION_EQUAL, static::TEST_PRODUCT_OFFER_REFERENCE);

        // Assert
        $this->expectException(NullValueException::class);
        $this->expectExceptionMessage('Property "priceMode" of transfer `Generated\Shared\Transfer\QuoteTransfer` is null.');

        // Act
        $this->tester
            ->getFacade()
            ->getDiscountableItemsByProductOfferReference($quoteTransfer, $clauseTransfer);
    }

    /**
     * @return void
     */
    public function testThrowsNulValueExceptionWhenItemUnitNetPriceIsNotSet(): void
    {
        // Arrange
        $itemTransfer = (new ItemTransfer())->setProductOfferReference(static::TEST_PRODUCT_OFFER_REFERENCE);
        $quoteTransfer = (new QuoteTransfer())->addItem($itemTransfer)->setPriceMode(static::PRICE_MODE_NET);
        $clauseTransfer = $this->tester->createClauseTransfer(static::EXPRESSION_EQUAL, static::TEST_PRODUCT_OFFER_REFERENCE);

        // Assert
        $this->expectException(NullValueException::class);
        $this->expectExceptionMessage('Property "unitNetPrice" of transfer `Generated\Shared\Transfer\ItemTransfer` is null.');

        // Act
        $this->tester
            ->getFacade()
            ->getDiscountableItemsByProductOfferReference($quoteTransfer, $clauseTransfer);
    }

    /**
     * @return void
     */
    public function testThrowsNulValueExceptionWhenItemUnitGrossPriceIsNotSet(): void
    {
        // Arrange
        $itemTransfer = (new ItemTransfer())->setProductOfferReference(static::TEST_PRODUCT_OFFER_REFERENCE);
        $quoteTransfer = (new QuoteTransfer())->addItem($itemTransfer)->setPriceMode(static::PRICE_MODE_GROSS);
        $clauseTransfer = $this->tester->createClauseTransfer(static::EXPRESSION_EQUAL, static::TEST_PRODUCT_OFFER_REFERENCE);

        // Assert
        $this->expectException(NullValueException::class);
        $this->expectExceptionMessage('Property "unitGrossPrice" of transfer `Generated\Shared\Transfer\ItemTransfer` is null.');

        // Act
        $this->tester
            ->getFacade()
            ->getDiscountableItemsByProductOfferReference($quoteTransfer, $clauseTransfer);
    }

    /**
     * @param list<\Generated\Shared\Transfer\DiscountableItemTransfer> $discountableItemTransfers
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\CalculatedDiscountTransfer $calculatedDiscountTransfer
     * @param string $priceMode
     *
     * @return void
     */
    protected function assertDiscountableItemProperties(
        array $discountableItemTransfers,
        ItemTransfer $itemTransfer,
        CalculatedDiscountTransfer $calculatedDiscountTransfer,
        string $priceMode
    ): void {
        /** @var \Generated\Shared\Transfer\DiscountableItemTransfer $discountableItemTransfer */
        $discountableItemTransfer = $discountableItemTransfers[0];
        $this->assertSame($itemTransfer->getIdSalesOrderItem(), $discountableItemTransfer->getOriginalItemOrFail()->getIdSalesOrderItem());
        $this->assertSame($priceMode === static::PRICE_MODE_NET ? $itemTransfer->getUnitNetPrice() : $itemTransfer->getUnitGrossPrice(), $discountableItemTransfer->getUnitPrice());
        $this->assertSame(
            $calculatedDiscountTransfer->getIdDiscount(),
            $discountableItemTransfer->getOriginalItemCalculatedDiscounts()->offsetGet(0)->getIdDiscount(),
        );
    }
}
