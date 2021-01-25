<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Cart\Business\StorageProvider;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Cart\Business\Exception\InvalidQuantityExeption;
use Spryker\Zed\Cart\Business\StorageProvider\NonPersistentProvider;
use SprykerTest\Zed\Cart\Business\Mocks\CartItemAddTripleStrategy;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Cart
 * @group Business
 * @group StorageProvider
 * @group NonPersistentProviderTest
 * Add your own group annotations below this line
 */
class NonPersistentProviderTest extends Unit
{
    public const COUPON_CODE_1 = 'coupon code 1';
    public const COUPON_CODE_2 = 'coupon code 2';

    /**
     * @var \Spryker\Zed\Cart\Business\StorageProvider\StorageProviderInterface
     */
    protected $provider;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->provider = new NonPersistentProvider([], []);
    }

    /**
     * @return void
     */
    public function testAddExistingItem(): void
    {
        // Arrange
        $itemId = '123';
        $existingQuantity = 1;
        $newQuantity = 3;

        $quoteTransfer = $this->createQuoteWithItem($itemId, $existingQuantity);
        $newItem = $this->createItem([
            ItemTransfer::ID => $itemId,
            ItemTransfer::SKU => $itemId,
            ItemTransfer::GROUP_KEY => $itemId,
            ItemTransfer::QUANTITY => $newQuantity,
        ]);
        $cartChangeTransfer = new CartChangeTransfer();
        $cartChangeTransfer->addItem($newItem);
        $cartChangeTransfer->setQuote($quoteTransfer);

        // Act
        $changedCart = $this->provider->addItems($cartChangeTransfer);

        // Assert
        $changedItems = $changedCart->getItems();
        $this->assertCount(1, $changedItems);

        $changedItem = $changedItems[0];

        $this->assertSame($itemId, $changedItem->getId());
        $this->assertSame(
            $existingQuantity + $newQuantity,
            $changedItem->getQuantity()
        );
    }

    /**
     * @return void
     */
    public function testAddNewItem(): void
    {
        // Arrange
        $itemId = '123';
        $newId = '321';
        $existingQuantity = 1;
        $newQuantity = 3;

        $quoteTransfer = $this->createQuoteWithItem($itemId, $existingQuantity);

        $newItem = $this->createItem([
            ItemTransfer::ID => $newId,
            ItemTransfer::SKU => $newId,
            ItemTransfer::GROUP_KEY => $newId,
            ItemTransfer::QUANTITY => $newQuantity,
        ]);
        $cartChangeTransfer = new CartChangeTransfer();
        $cartChangeTransfer->addItem($newItem);
        $cartChangeTransfer->setQuote($quoteTransfer);

        // Act
        $changedCart = $this->provider->addItems($cartChangeTransfer);

        // Assert
        $changedItems = $changedCart->getItems();
        $this->assertCount(2, $changedItems);

        $skuIndex = [];
        foreach ($changedItems as $key => $changedItem) {
            $skuIndex[$changedItem->getId()] = $key;
        }

        $this->assertArrayHasKey($itemId, $skuIndex);
        $this->assertArrayHasKey($newId, $skuIndex);

        $addedItem = $changedItems[$skuIndex[$newId]];
        $this->assertSame($newId, $addedItem->getId());
        $this->assertSame($newQuantity, $addedItem->getQuantity());

        $existingItem = $changedItems[$skuIndex[$itemId]];
        $this->assertSame($itemId, $existingItem->getId());
        $this->assertSame($existingQuantity, $existingItem->getQuantity());
    }

    /**
     * @return void
     */
    public function testAddDoubleNewItem(): void
    {
        // Arrange
        $existingItemId = '123';
        $newItemId = '321';
        $existingItemQuantity = 1;
        $newFirstItemQuantity = 3;
        $newSecondItemQuantity = 4;

        $quoteTransfer = $this->createQuoteWithItem($existingItemId, $existingItemQuantity);

        $newFirstItem = $this->createItem([
            ItemTransfer::ID => $newItemId,
            ItemTransfer::SKU => $newItemId,
            ItemTransfer::GROUP_KEY => $newItemId,
            ItemTransfer::QUANTITY => $newFirstItemQuantity,
        ]);
        $newSecondItem = $this->createItem([
            ItemTransfer::ID => $newItemId,
            ItemTransfer::SKU => $newItemId,
            ItemTransfer::GROUP_KEY => $newItemId,
            ItemTransfer::QUANTITY => $newSecondItemQuantity,
        ]);
        $cartChangeTransfer = new CartChangeTransfer();
        $cartChangeTransfer->addItem($newFirstItem);
        $cartChangeTransfer->addItem($newSecondItem);
        $cartChangeTransfer->setQuote($quoteTransfer);

        // Act
        $changedCart = $this->provider->addItems($cartChangeTransfer);

        // Assert
        $changedItems = $changedCart->getItems();
        $this->assertCount(2, $changedItems);

        $skuIndex = [];
        foreach ($changedItems as $key => $changedItem) {
            $skuIndex[$changedItem->getId()] = $key;
        }

        $this->assertArrayHasKey($existingItemId, $skuIndex);
        $this->assertArrayHasKey($newItemId, $skuIndex);

        $addedItem = $changedItems[$skuIndex[$newItemId]];
        $this->assertSame($newItemId, $addedItem->getId());
        $this->assertSame($newFirstItemQuantity + $newSecondItemQuantity, $addedItem->getQuantity());

        $existingItem = $changedItems[$skuIndex[$existingItemId]];
        $this->assertSame($existingItemId, $existingItem->getId());
        $this->assertSame($existingItemQuantity, $existingItem->getQuantity());
    }

    /**
     * @return void
     */
    public function testRemoveExistingItem(): void
    {
        // Arrange
        $itemId = '123';
        $existingQuantity = 1;
        $reduceQuantity = 1;

        $quoteTransfer = $this->createQuoteWithItem($itemId, $existingQuantity);
        $newItem = $this->createItem([
            ItemTransfer::ID => $itemId,
            ItemTransfer::SKU => $itemId,
            ItemTransfer::GROUP_KEY => $itemId,
            ItemTransfer::QUANTITY => $reduceQuantity,
        ]);
        $cartChangeTransfer = new CartChangeTransfer();
        $cartChangeTransfer->addItem($newItem);
        $cartChangeTransfer->setQuote($quoteTransfer);

        // Act
        $changedCart = $this->provider->removeItems($cartChangeTransfer);

        // Assert
        $this->assertCount(0, $changedCart->getItems());
    }

    /**
     * @return void
     */
    public function testRemoveNotExistingItem(): void
    {
        // Arrange
        $itemId = '123';
        $existingQuantity = 1;
        $reduceQuantity = 1;
        $deleteItemId = '321';

        $quoteTransfer = $this->createQuoteWithItem($itemId, $existingQuantity);
        $newItem = $this->createItem([
            ItemTransfer::ID => $deleteItemId,
            ItemTransfer::SKU => $deleteItemId,
            ItemTransfer::GROUP_KEY => $deleteItemId,
            ItemTransfer::QUANTITY => $reduceQuantity,
        ]);
        $cartChangeTransfer = new CartChangeTransfer();
        $cartChangeTransfer->addItem($newItem);
        $cartChangeTransfer->setQuote($quoteTransfer);

        // Act
        $changedCart = $this->provider->removeItems($cartChangeTransfer);

        // Assert
        $changedItems = $changedCart->getItems();
        $this->assertCount(1, $changedItems);
        $item = $changedItems[0];
        $this->assertSame($itemId, $item->getId());
        $this->assertSame($existingQuantity, $item->getQuantity());
    }

    /**
     * @return void
     */
    public function testReduceWithMoreThenExists(): void
    {
        // Arrange
        $itemId = '123';
        $existingQuantity = 1;
        $reduceQuantity = 3;

        $quoteTransfer = $this->createQuoteWithItem($itemId, $existingQuantity);
        $newItem = $this->createItem([
            ItemTransfer::ID => $itemId,
            ItemTransfer::SKU => $itemId,
            ItemTransfer::GROUP_KEY => $itemId,
            ItemTransfer::QUANTITY => $reduceQuantity,
        ]);
        $cartChangeTransfer = new CartChangeTransfer();
        $cartChangeTransfer->addItem($newItem);
        $cartChangeTransfer->setQuote($quoteTransfer);

        // Act
        $changedCart = $this->provider->removeItems($cartChangeTransfer);

        // Assert
        $this->assertCount(0, $changedCart->getItems());
    }

    /**
     * @return void
     */
    public function testIncreaseWithNegativeValue(): void
    {
        // Arrange
        $this->expectException(InvalidQuantityExeption::class);
        $this->expectExceptionMessage('Could not change the quantity of cart item "123" to "-3".');
        $itemId = '123';
        $newId = '123';
        $existingQuantity = 1;
        $newQuantity = -3;

        $quoteTransfer = $this->createQuoteWithItem($itemId, $existingQuantity);

        $newItem = $this->createItem([
            ItemTransfer::ID => $newId,
            ItemTransfer::SKU => $newId,
            ItemTransfer::GROUP_KEY => $newId,
            ItemTransfer::QUANTITY => $newQuantity,
        ]);
        $cartChangeTransfer = new CartChangeTransfer();
        $cartChangeTransfer->addItem($newItem);
        $cartChangeTransfer->setQuote($quoteTransfer);

        // Act
        $this->provider->addItems($cartChangeTransfer);
    }

    /**
     * @return void
     */
    public function testIncreaseWithZeroValue(): void
    {
        // Arrange
        $this->expectException(InvalidQuantityExeption::class);
        $this->expectExceptionMessage('Could not change the quantity of cart item "123" to "0".');
        $itemId = '123';
        $newId = '123';
        $existingQuantity = 1;
        $newQuantity = 0;

        $quoteTransfer = $this->createQuoteWithItem($itemId, $existingQuantity);

        $newItem = $this->createItem([
            ItemTransfer::ID => $newId,
            ItemTransfer::SKU => $newId,
            ItemTransfer::GROUP_KEY => $newId,
            ItemTransfer::QUANTITY => $newQuantity,
        ]);
        $cartChangeTransfer = new CartChangeTransfer();
        $cartChangeTransfer->addItem($newItem);
        $cartChangeTransfer->setQuote($quoteTransfer);

        // Act
        $this->provider->addItems($cartChangeTransfer);
    }

    /**
     * @return void
     */
    public function testDecreaseWithNegativeValue(): void
    {
        // Arrange
        $this->expectException(InvalidQuantityExeption::class);
        $this->expectExceptionMessage('Could not change the quantity of cart item "123" to "-3".');
        $itemId = '123';
        $newId = '123';
        $existingQuantity = 1;
        $newQuantity = -3;

        $quoteTransfer = $this->createQuoteWithItem($itemId, $existingQuantity);

        $newItem = $this->createItem([
            ItemTransfer::ID => $newId,
            ItemTransfer::SKU => $newId,
            ItemTransfer::GROUP_KEY => $newId,
            ItemTransfer::QUANTITY => $newQuantity,
        ]);
        $cartChangeTransfer = new CartChangeTransfer();
        $cartChangeTransfer->addItem($newItem);
        $cartChangeTransfer->setQuote($quoteTransfer);

        // Act
        $this->provider->removeItems($cartChangeTransfer);
    }

    /**
     * @return void
     */
    public function testDecreaseWithZeroValue(): void
    {
        // Arrange
        $this->expectException(InvalidQuantityExeption::class);
        $this->expectExceptionMessage('Could not change the quantity of cart item "123" to "0".');
        $itemId = '123';
        $newId = '123';
        $existingQuantity = 1;
        $newQuantity = 0;

        $quoteTransfer = $this->createQuoteWithItem($itemId, $existingQuantity);

        $newItem = $this->createItem([
            ItemTransfer::ID => $newId,
            ItemTransfer::SKU => $newId,
            ItemTransfer::GROUP_KEY => $newId,
            ItemTransfer::QUANTITY => $newQuantity,
        ]);
        $cartChangeTransfer = new CartChangeTransfer();
        $cartChangeTransfer->addItem($newItem);
        $cartChangeTransfer->setQuote($quoteTransfer);

        // Act
        $this->provider->removeItems($cartChangeTransfer);
    }

    /**
     * @return void
     */
    public function testWithCustomCartAddItemStrategy(): void
    {
        // Arrange
        $provider = new NonPersistentProvider([
            new CartItemAddTripleStrategy(),
        ], []);

        $existingItemId = '123';
        $newItemId = '321';
        $existingItemQuantity = 1;
        $newFirstItemQuantity = 3;
        $newSecondItemQuantity = 4;

        $quoteTransfer = $this->createQuoteWithItem($existingItemId, $existingItemQuantity);

        $newFirstItem = $this->createItem([
            ItemTransfer::ID => $newItemId,
            ItemTransfer::SKU => $newItemId,
            ItemTransfer::GROUP_KEY => $newItemId,
            ItemTransfer::QUANTITY => $newFirstItemQuantity,
        ]);
        $newSecondItem = $this->createItem([
            ItemTransfer::ID => $newItemId,
            ItemTransfer::SKU => $newItemId,
            ItemTransfer::GROUP_KEY => $newItemId,
            ItemTransfer::QUANTITY => $newSecondItemQuantity,
        ]);
        $cartChangeTransfer = new CartChangeTransfer();
        $cartChangeTransfer->addItem($newFirstItem);
        $cartChangeTransfer->addItem($newSecondItem);
        $cartChangeTransfer->setQuote($quoteTransfer);

        // Act
        $changedCart = $provider->addItems($cartChangeTransfer);

        // Assert
        $changedItems = $changedCart->getItems();
        $this->assertCount(2, $changedItems);

        $skuIndex = [];
        foreach ($changedItems as $key => $changedItem) {
            $skuIndex[$changedItem->getId()] = $key;
        }

        $addedItem = $changedItems[$skuIndex[$newItemId]];
        $this->assertSame($newItemId, $addedItem->getId());
        $this->assertSame(($newFirstItemQuantity + $newSecondItemQuantity) * 3, $addedItem->getQuantity());

        $existingItem = $changedItems[$skuIndex[$existingItemId]];
        $this->assertSame($existingItemId, $existingItem->getId());
        $this->assertSame($existingItemQuantity, $existingItem->getQuantity());
    }

    /**
     * @return void
     */
    public function testAddExistingItemWithNewPrice(): void
    {
        // Arrange
        $itemId = '123';
        $newId = '123';
        $existingQuantity = 1;
        $newQuantity = 1;
        $existingPrice = 100;
        $newPrice = 99;

        $quoteTransfer = $this->createQuoteWithItem($itemId, $existingQuantity, $existingPrice);

        $newItem = $this->createItem([
            ItemTransfer::ID => $newId,
            ItemTransfer::SKU => $newId,
            ItemTransfer::GROUP_KEY => $newId,
            ItemTransfer::QUANTITY => $newQuantity,
            ItemTransfer::UNIT_GROSS_PRICE => $newPrice,
        ]);
        $cartChangeTransfer = new CartChangeTransfer();
        $cartChangeTransfer->addItem($newItem);
        $cartChangeTransfer->setQuote($quoteTransfer);

        // Act
        $actualQuoteTransfer = $this->provider->addItems($cartChangeTransfer);

        // Assert
        $this->assertCount(1, $actualQuoteTransfer->getItems());

        /** @var \Generated\Shared\Transfer\ItemTransfer $actualItemTransfer */
        $actualItemTransfer = $actualQuoteTransfer->getItems()->offsetGet(0);
        $this->assertSame(2, $actualItemTransfer->getQuantity());
        $this->assertSame($newPrice, $actualItemTransfer->getUnitGrossPrice());
    }

    /**
     * @return void
     */
    public function testReduceItemWithNewPrice(): void
    {
        // Arrange
        $itemId = '123';
        $newId = '123';
        $existingQuantity = 2;
        $reducedQuantity = 1;
        $existingPrice = 99;
        $newPrice = 101;

        $quoteTransfer = $this->createQuoteWithItem($itemId, $existingQuantity, $existingPrice);

        $newItem = $this->createItem([
            ItemTransfer::ID => $newId,
            ItemTransfer::SKU => $newId,
            ItemTransfer::GROUP_KEY => $newId,
            ItemTransfer::QUANTITY => $reducedQuantity,
            ItemTransfer::UNIT_GROSS_PRICE => $newPrice,
        ]);
        $cartChangeTransfer = new CartChangeTransfer();
        $cartChangeTransfer->addItem($newItem);
        $cartChangeTransfer->setQuote($quoteTransfer);

        // Act
        $actualQuoteTransfer = $this->provider->removeItems($cartChangeTransfer);

        // Assert
        $this->assertCount(1, $actualQuoteTransfer->getItems());

        /** @var \Generated\Shared\Transfer\ItemTransfer $actualItemTransfer */
        $actualItemTransfer = $actualQuoteTransfer->getItems()->offsetGet(0);
        $this->assertSame(1, $actualItemTransfer->getQuantity());
        $this->assertSame($newPrice, $actualItemTransfer->getUnitGrossPrice());
    }

    /**
     * @param string $itemId
     * @param int $itemQuantity
     * @param int|null $unitGrossPrice
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteWithItem(string $itemId, int $itemQuantity, ?int $unitGrossPrice = null): QuoteTransfer
    {
        $cart = new QuoteTransfer();
        $existingItem = $this->createItem([
            ItemTransfer::ID => $itemId,
            ItemTransfer::SKU => $itemId,
            ItemTransfer::GROUP_KEY => $itemId,
            ItemTransfer::QUANTITY => $itemQuantity,
            ItemTransfer::UNIT_GROSS_PRICE => $unitGrossPrice,
        ]);
        $cart->addItem($existingItem);

        return $cart;
    }

    /**
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function createItem(array $seed = []): ItemTransfer
    {
        return (new ItemBuilder($seed))->build();
    }
}
