<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Cart\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\FlashMessagesTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\QuoteValidationResponseTransfer;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery;
use Orm\Zed\PriceProduct\Persistence\SpyPriceTypeQuery;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Spryker\Zed\Cart\CartDependencyProvider;
use Spryker\Zed\Cart\Dependency\Facade\CartToMessengerInterface;
use Spryker\Zed\Cart\Dependency\Facade\CartToQuoteFacadeInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Cart
 * @group Business
 * @group Facade
 * @group CartFacadeTest
 * Add your own group annotations below this line
 */
class CartFacadeTest extends Unit
{
    public const PRICE_TYPE_DEFAULT = 'DEFAULT';
    public const DUMMY_1_SKU_ABSTRACT_PRODUCT = 'ABSTRACT1';
    public const DUMMY_1_SKU_CONCRETE_PRODUCT = 'CONCRETE1';
    public const DUMMY_1_PRICE = 99;
    public const DUMMY_2_SKU_ABSTRACT_PRODUCT = 'ABSTRACT2';
    public const DUMMY_2_SKU_CONCRETE_PRODUCT = 'CONCRETE2';
    public const DUMMY_2_PRICE = 100;

    /**
     * @var \SprykerTest\Zed\Cart\CartBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->setTestData();
    }

    /**
     * @return void
     */
    public function testAddToCart(): void
    {
        $quoteTransfer = new QuoteTransfer();
        $cartItem = new ItemTransfer();
        $cartItem->setSku(self::DUMMY_1_SKU_CONCRETE_PRODUCT);
        $cartItem->setQuantity(3);
        $cartItem->setUnitGrossPrice(1);

        $quoteTransfer->addItem($cartItem);

        $newItem = new ItemTransfer();
        $newItem->setSku(self::DUMMY_2_SKU_CONCRETE_PRODUCT);
        $newItem->setQuantity(1);
        $newItem->setUnitGrossPrice(1);

        $cartChange = new CartChangeTransfer();
        $cartChange->setQuote($quoteTransfer);
        $cartChange->addItem($newItem);

        $changedCart = $this->getCartFacade()->add($cartChange);

        $this->assertCount(2, $changedCart->getItems());

        /** @var \Generated\Shared\Transfer\ItemTransfer $item */
        foreach ($quoteTransfer->getItems() as $item) {
            if ($item->getSku() === $cartItem->getSku()) {
                $this->assertEquals($cartItem->getQuantity(), $item->getQuantity());
            } elseif ($newItem->getSku() === $item->getSku()) {
                $this->assertEquals($newItem->getQuantity(), $item->getQuantity());
            } else {
                $this->fail('Cart has a unknown item inside');
            }
        }
    }

    /**
     * @return void
     */
    public function testRemoveFromCart(): void
    {
        $quoteTransfer = new QuoteTransfer();
        $cartItem = new ItemTransfer();
        $cartItem->setId(self::DUMMY_2_SKU_CONCRETE_PRODUCT);
        $cartItem->setSku(self::DUMMY_2_SKU_CONCRETE_PRODUCT);
        $cartItem->setQuantity(1);
        $cartItem->setUnitGrossPrice(1);

        $quoteTransfer->addItem($cartItem);

        $newItem = new ItemTransfer();
        $newItem->setId(self::DUMMY_2_SKU_CONCRETE_PRODUCT);
        $newItem->setSku(self::DUMMY_2_SKU_CONCRETE_PRODUCT);
        $newItem->setQuantity(1);
        $newItem->setUnitGrossPrice(1);

        $cartChange = new CartChangeTransfer();
        $cartChange->setQuote($quoteTransfer);
        $cartChange->addItem($newItem);

        $changedCart = $this->getCartFacade()->remove($cartChange);

        $this->assertCount(0, $changedCart->getItems());
    }

    /**
     * @return void
     */
    public function testReloadItemsInQuoteReturnsCorrectData(): void
    {
        // Arrange
        $quoteTransfer = new QuoteTransfer();
        $itemTransfer = (new ItemTransfer())
            ->setId(self::DUMMY_1_SKU_CONCRETE_PRODUCT)
            ->setSku(self::DUMMY_1_SKU_CONCRETE_PRODUCT)
            ->setQuantity(1)
            ->setUnitGrossPrice(self::DUMMY_1_PRICE);
        $quoteTransfer->addItem($itemTransfer);
        $itemTransfer = (new ItemTransfer())
            ->setId(self::DUMMY_2_SKU_CONCRETE_PRODUCT)
            ->setSku(self::DUMMY_2_SKU_CONCRETE_PRODUCT)
            ->setQuantity(1)
            ->setUnitGrossPrice(self::DUMMY_2_PRICE);
        $quoteTransfer->addItem($itemTransfer);

        $quoteValidationResponseTransfer = (new QuoteValidationResponseTransfer())
            ->setIsSuccessful(true);

        $quoteFacadeMock = $this->getQuoteFacadeMock();
        $quoteFacadeMock->expects($this->once())
            ->method('isQuoteLocked')
            ->willReturn(false);
        $quoteFacadeMock->expects($this->once())
            ->method('validateQuote')
            ->willReturn($quoteValidationResponseTransfer);

        $messengerFacadeMock = $this->getMessengerFacadeMock();
        $messengerFacadeMock->expects($this->never())
            ->method('getStoredMessages');

        // Act
        $quoteResponseTransfer = $this->getCartFacade()->reloadItemsInQuote($quoteTransfer);

        // Assert
        $this->assertTrue($quoteResponseTransfer->getIsSuccessful());
        $this->assertCount(2, $quoteResponseTransfer->getQuoteTransfer()->getItems());
    }

    /**
     * @return void
     */
    public function testReloadItemsInQuoteReturnsUnsuccessfulQuoteResponseWithErrorMessageOnLockedQuote(): void
    {
        // Arrange
        $quoteTransfer = new QuoteTransfer();
        $itemTransfer = (new ItemTransfer())
            ->setId(self::DUMMY_1_SKU_CONCRETE_PRODUCT)
            ->setSku(self::DUMMY_1_SKU_CONCRETE_PRODUCT)
            ->setQuantity(1)
            ->setUnitGrossPrice(self::DUMMY_1_PRICE);
        $quoteTransfer->addItem($itemTransfer);

        $quoteFacadeMock = $this->getQuoteFacadeMock();
        $quoteFacadeMock->expects($this->once())
            ->method('isQuoteLocked')
            ->willReturn(true);
        $quoteFacadeMock->expects($this->never())
            ->method('validateQuote');

        $messengerFacadeMock = $this->getMessengerFacadeMock();
        $messengerFacadeMock->expects($this->once())
            ->method('getStoredMessages')
            ->willReturn((new FlashMessagesTransfer()));

        // Act
        $quoteResponseTransfer = $this->getCartFacade()->reloadItemsInQuote($quoteTransfer);

        // Assert
        $this->assertFalse($quoteResponseTransfer->getIsSuccessful());
        $this->assertCount(1, $quoteResponseTransfer->getQuoteTransfer()->getItems());
    }

    /**
     * @return void
     */
    public function testReloadItemsInQuoteReturnsUnsuccessfulQuoteResponseWithErrorMessageOnInvalidItem(): void
    {
        // Arrange
        $quoteTransfer = new QuoteTransfer();
        $itemTransfer = (new ItemTransfer())
            ->setId(self::DUMMY_1_SKU_CONCRETE_PRODUCT)
            ->setSku(self::DUMMY_1_SKU_CONCRETE_PRODUCT)
            ->setQuantity(1)
            ->setUnitGrossPrice(self::DUMMY_1_PRICE);
        $quoteTransfer->addItem($itemTransfer);

        $quoteValidationResponseTransfer = (new QuoteValidationResponseTransfer())
            ->setIsSuccessful(false);

        $quoteFacadeMock = $this->getQuoteFacadeMock();
        $quoteFacadeMock->expects($this->once())
            ->method('isQuoteLocked')
            ->willReturn(false);
        $quoteFacadeMock->expects($this->once())
            ->method('validateQuote')
            ->willReturn($quoteValidationResponseTransfer);

        $messengerFacadeMock = $this->getMessengerFacadeMock();
        $messengerFacadeMock->expects($this->once())
            ->method('getStoredMessages')
            ->willReturn((new FlashMessagesTransfer()));

        // Act
        $quoteResponseTransfer = $this->getCartFacade()->reloadItemsInQuote($quoteTransfer);

        // Assert
        $this->assertFalse($quoteResponseTransfer->getIsSuccessful());
        $this->assertCount(1, $quoteResponseTransfer->getQuoteTransfer()->getItems());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Cart\Dependency\Facade\CartToQuoteFacadeInterface
     */
    protected function getQuoteFacadeMock(): CartToQuoteFacadeInterface
    {
        $quoteFacadeMock = $this
            ->getMockBuilder(CartToQuoteFacadeInterface::class)
            ->getMock();

        $this->tester->setDependency(
            CartDependencyProvider::FACADE_QUOTE,
            $quoteFacadeMock
        );

        return $quoteFacadeMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Cart\Dependency\Facade\CartToMessengerInterface
     */
    protected function getMessengerFacadeMock(): CartToMessengerInterface
    {
        $messengerFacadeMock = $this
            ->getMockBuilder(CartToMessengerInterface::class)
            ->getMock();

        $this->tester->setDependency(
            CartDependencyProvider::FACADE_MESSENGER,
            $messengerFacadeMock
        );

        return $messengerFacadeMock;
    }

    /**
     * @return \Spryker\Zed\Cart\Business\CartFacadeInterface|\Spryker\Zed\Kernel\Business\AbstractFacade
     */
    protected function getCartFacade()
    {
        return $this->tester->getFacade();
    }

    /**
     * @return void
     */
    public function testCleanUpItemsRemoveKeyGroupPrefixFromQuoteItem(): void
    {
        // Arrange
        $quoteTransfer = new QuoteTransfer();
        $cartItem = (new ItemTransfer())->setSku(self::DUMMY_1_SKU_CONCRETE_PRODUCT)
            ->setQuantity(3)
            ->setUnitGrossPrice(1)
            ->setGroupKeyPrefix(uniqid('', true));

        $quoteTransfer->addItem($cartItem);

        // Act
        $this->getCartFacade()->cleanUpItems($quoteTransfer);

        // Assert
        $this->assertNull($quoteTransfer->getItems()[0]->getGroupKeyPrefix());
    }

    /**
     * @return void
     */
    public function testCleanUpItemsRemoveKeyGroupPrefixFromQuoteItemIfMoreThanOne(): void
    {
        // Arrange
        $quoteTransfer = new QuoteTransfer();
        $cartItem = (new ItemTransfer())->setSku(self::DUMMY_1_SKU_CONCRETE_PRODUCT)
            ->setQuantity(3)
            ->setUnitGrossPrice(1)
            ->setGroupKeyPrefix(uniqid('', true));

        $newItem = (new ItemTransfer())->setSku(self::DUMMY_1_SKU_CONCRETE_PRODUCT)
            ->setQuantity(1)
            ->setUnitGrossPrice(1);

        $quoteTransfer->addItem($cartItem);
        $quoteTransfer->addItem($newItem);

        // Act
        $this->getCartFacade()->cleanUpItems($quoteTransfer);

        // Assert
        $this->assertNotNull($quoteTransfer->getItems()[0]->getGroupKeyPrefix());
    }

    /**
     * @return void
     */
    protected function setTestData(): void
    {
        $defaultPriceType = SpyPriceTypeQuery::create()->filterByName(self::PRICE_TYPE_DEFAULT)->findOneOrCreate();
        $defaultPriceType->setName(self::PRICE_TYPE_DEFAULT)->save();

        $abstractProduct1 = SpyProductAbstractQuery::create()
            ->filterBySku(self::DUMMY_1_SKU_ABSTRACT_PRODUCT)
            ->findOneOrCreate();

        $abstractProduct1->setSku(self::DUMMY_1_SKU_ABSTRACT_PRODUCT)
            ->setAttributes('{}')
            ->save();

        $concreteProduct1 = SpyProductQuery::create()
            ->filterBySku(self::DUMMY_1_SKU_CONCRETE_PRODUCT)
            ->findOneOrCreate();

        $concreteProduct1
            ->setSku(self::DUMMY_1_SKU_CONCRETE_PRODUCT)
            ->setSpyProductAbstract($abstractProduct1)
            ->setAttributes('{}')
            ->save();

        $abstractProduct2 = SpyProductAbstractQuery::create()
            ->filterBySku(self::DUMMY_2_SKU_ABSTRACT_PRODUCT)
            ->findOneOrCreate();

        $abstractProduct2
            ->setSku(self::DUMMY_2_SKU_ABSTRACT_PRODUCT)
            ->setAttributes('{}')
            ->save();

        $concreteProduct2 = SpyProductQuery::create()
            ->filterBySku(self::DUMMY_2_SKU_CONCRETE_PRODUCT)
            ->findOneOrCreate();

        $concreteProduct2
            ->setSku(self::DUMMY_2_SKU_CONCRETE_PRODUCT)
            ->setSpyProductAbstract($abstractProduct2)
            ->setAttributes('{}')
            ->save();

        SpyPriceProductQuery::create()
            ->filterByProduct($concreteProduct1)
            ->filterBySpyProductAbstract($abstractProduct1)
            ->filterByPriceType($defaultPriceType)
            ->findOneOrCreate()
            ->setPrice(100)
            ->save();

        SpyPriceProductQuery::create()
            ->filterByProduct($concreteProduct2)
            ->filterBySpyProductAbstract($abstractProduct2)
            ->filterByPriceType($defaultPriceType)
            ->findOneOrCreate()
            ->setPrice(100)
            ->save();
    }
}
