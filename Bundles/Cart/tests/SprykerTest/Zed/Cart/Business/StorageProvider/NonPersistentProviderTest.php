<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Cart\Business\StorageProvider;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Cart\Business\StorageProvider\NonPersistentProvider;
use SprykerTest\Zed\Cart\Business\Mocks\CartItemAddTripleStrategy;

/**
 * Auto-generated group annotations
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
    private $provider;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();
        $this->provider = new NonPersistentProvider([], []);
    }

    /**
     * @dataProvider itemQuantitiesDataProvider
     *
     * @param int|float $existingQuantity
     * @param int|float $newQuantity
     *
     * @return void
     */
    public function testAddExistingItem($existingQuantity, $newQuantity): void
    {
        $itemId = '123';

        $quoteTransfer = $this->createQuoteWithItem($itemId, $existingQuantity);
        $newItem = $this->createItem($itemId, $newQuantity);
        $cartChangeTransfer = new CartChangeTransfer();
        $cartChangeTransfer->addItem($newItem);
        $cartChangeTransfer->setQuote($quoteTransfer);

        $changedCart = $this->provider->addItems($cartChangeTransfer);
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
     * @dataProvider twoItemQuantitiesDataProvider
     *
     * @param int|float $existingQuantity
     * @param int|float $newQuantity
     *
     * @return void
     */
    public function testAddNewItem($existingQuantity, $newQuantity): void
    {
        $itemId = '123';
        $newId = '321';

        $quoteTransfer = $this->createQuoteWithItem($itemId, $existingQuantity);

        $newItem = $this->createItem($newId, $newQuantity);
        $cartChangeTransfer = new CartChangeTransfer();
        $cartChangeTransfer->addItem($newItem);
        $cartChangeTransfer->setQuote($quoteTransfer);

        $changedCart = $this->provider->addItems($cartChangeTransfer);
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
     * @dataProvider thereItemQuantitiesDataProvider
     *
     * @param int|float $existingItemQuantity
     * @param int|float $newFirstItemQuantity
     * @param int|float $newSecondItemQuantity
     *
     * @return void
     */
    public function testAddDoubleNewItem(
        $existingItemQuantity,
        $newFirstItemQuantity,
        $newSecondItemQuantity
    ): void {
        $existingItemId = '123';
        $newItemId = '321';

        $quoteTransfer = $this->createQuoteWithItem($existingItemId, $existingItemQuantity);

        $newFirstItem = $this->createItem($newItemId, $newFirstItemQuantity);
        $newSecondItem = $this->createItem($newItemId, $newSecondItemQuantity);
        $cartChangeTransfer = new CartChangeTransfer();
        $cartChangeTransfer->addItem($newFirstItem);
        $cartChangeTransfer->addItem($newSecondItem);
        $cartChangeTransfer->setQuote($quoteTransfer);

        $changedCart = $this->provider->addItems($cartChangeTransfer);
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
     * @return array
     */
    public function thereItemQuantitiesDataProvider(): array
    {
        return [
            'int stock' => [1, 3, 4],
            'float stock' => [1.1, 3.3, 4.4],
        ];
    }

    /**
     * @dataProvider twoItemQuantitiesDataProvider
     *
     * @param int|float $existingQuantity
     * @param int|float $reduceQuantity
     *
     * @return void
     */
    public function testRemoveExistingItem($existingQuantity, $reduceQuantity): void
    {
        $itemId = '123';

        $quoteTransfer = $this->createQuoteWithItem($itemId, $existingQuantity);
        $newItem = $this->createItem($itemId, $reduceQuantity);
        $cartChangeTransfer = new CartChangeTransfer();
        $cartChangeTransfer->addItem($newItem);
        $cartChangeTransfer->setQuote($quoteTransfer);

        $changedCart = $this->provider->removeItems($cartChangeTransfer);
        $this->assertCount(0, $changedCart->getItems());
    }

    /**
     * @dataProvider twoItemQuantitiesDataProvider
     *
     * @param int|float $existingQuantity
     * @param int|float $reduceQuantity
     *
     * @return void
     */
    public function testRemoveNotExistingItem($existingQuantity, $reduceQuantity): void
    {
        $itemId = '123';
        $deleteItemId = '321';

        $quoteTransfer = $this->createQuoteWithItem($itemId, $existingQuantity);
        $newItem = $this->createItem($deleteItemId, $reduceQuantity);
        $cartChangeTransfer = new CartChangeTransfer();
        $cartChangeTransfer->addItem($newItem);
        $cartChangeTransfer->setQuote($quoteTransfer);

        $changedCart = $this->provider->removeItems($cartChangeTransfer);
        $changedItems = $changedCart->getItems();
        $this->assertCount(1, $changedItems);
        $item = $changedItems[0];
        $this->assertSame($itemId, $item->getId());
        $this->assertSame($existingQuantity, $item->getQuantity());
    }

    /**
     * @dataProvider twoItemQuantitiesDataProvider
     *
     * @param int|float $existingQuantity
     * @param int|float $reduceQuantity
     *
     * @return void
     */
    public function testReduceWithMoreThenExists($existingQuantity, $reduceQuantity): void
    {
        $itemId = '123';

        $quoteTransfer = $this->createQuoteWithItem($itemId, $existingQuantity);
        $newItem = $this->createItem($itemId, $reduceQuantity);
        $cartChangeTransfer = new CartChangeTransfer();
        $cartChangeTransfer->addItem($newItem);
        $cartChangeTransfer->setQuote($quoteTransfer);

        $changedCart = $this->provider->removeItems($cartChangeTransfer);
        $this->assertCount(0, $changedCart->getItems());
    }

    /**
     * @return array
     */
    public function twoItemQuantitiesDataProvider(): array
    {
        return [
            'int stock' => [1, 3],
            'float stock' => [1.1, 3.3],
        ];
    }

    /**
     * @dataProvider twoWithNegativeItemQuantitiesDataProvider
     *
     * @expectedException \Spryker\Zed\Cart\Business\Exception\InvalidQuantityExeption
     * @expectedExceptionMessage Could not change the quantity of cart item "123" to "-3".
     *
     * @param int|float $existingQuantity
     * @param int|float $newQuantity
     *
     * @return void
     */
    public function testIncreaseWithNegativeValue($existingQuantity, $newQuantity): void
    {
        $itemId = '123';
        $newId = '123';

        $quoteTransfer = $this->createQuoteWithItem($itemId, $existingQuantity);

        $newItem = $this->createItem($newId, $newQuantity);
        $cartChangeTransfer = new CartChangeTransfer();
        $cartChangeTransfer->addItem($newItem);
        $cartChangeTransfer->setQuote($quoteTransfer);

        $this->provider->addItems($cartChangeTransfer);
    }

    /**
     * @return array
     */
    public function twoWithNegativeItemQuantitiesDataProvider(): array
    {
        return [
            'int stock' => [1, -3],
            'float stock' => [1.1, -3.3],
        ];
    }

    /**
     * @dataProvider twoWithZeroItemQuantitiesDataProvider
     *
     * @expectedException \Spryker\Zed\Cart\Business\Exception\InvalidQuantityExeption
     * @expectedExceptionMessage Could not change the quantity of cart item "123" to "0".
     *
     * @param int|float $existingQuantity
     * @param int|float $newQuantity
     *
     * @return void
     */
    public function testIncreaseWithZeroValue($existingQuantity, $newQuantity): void
    {
        $itemId = '123';
        $newId = '123';

        $quoteTransfer = $this->createQuoteWithItem($itemId, $existingQuantity);

        $newItem = $this->createItem($newId, $newQuantity);
        $cartChangeTransfer = new CartChangeTransfer();
        $cartChangeTransfer->addItem($newItem);
        $cartChangeTransfer->setQuote($quoteTransfer);

        $this->provider->addItems($cartChangeTransfer);
    }

    /**
     * @return array
     */
    public function twoWithZeroItemQuantitiesDataProvider(): array
    {
        return [
            'int stock' => [1, 0],
            'float stock' => [1.1, 0.0],
        ];
    }

    /**
     * @dataProvider twoWithNegativeItemQuantitiesDataProvider
     *
     * @expectedException \Spryker\Zed\Cart\Business\Exception\InvalidQuantityExeption
     * @expectedExceptionMessage Could not change the quantity of cart item "123" to "-3".
     *
     * @param int|float $existingQuantity
     * @param int|float $newQuantity
     *
     * @return void
     */
    public function testDecreaseWithNegativeValue($existingQuantity, $newQuantity): void
    {
        $itemId = '123';
        $newId = '123';

        $quoteTransfer = $this->createQuoteWithItem($itemId, $existingQuantity);

        $newItem = $this->createItem($newId, $newQuantity);
        $cartChangeTransfer = new CartChangeTransfer();
        $cartChangeTransfer->addItem($newItem);
        $cartChangeTransfer->setQuote($quoteTransfer);

        $this->provider->removeItems($cartChangeTransfer);
    }

    /**
     * @dataProvider twoWithZeroItemQuantitiesDataProvider
     *
     * @expectedException \Spryker\Zed\Cart\Business\Exception\InvalidQuantityExeption
     * @expectedExceptionMessage Could not change the quantity of cart item "123" to "0".
     *
     * @param int|float $existingQuantity
     * @param int|float $newQuantity
     *
     * @return void
     */
    public function testDecreaseWithZeroValue($existingQuantity, $newQuantity): void
    {
        $itemId = '123';
        $newId = '123';

        $quoteTransfer = $this->createQuoteWithItem($itemId, $existingQuantity);

        $newItem = $this->createItem($newId, $newQuantity);
        $cartChangeTransfer = new CartChangeTransfer();
        $cartChangeTransfer->addItem($newItem);
        $cartChangeTransfer->setQuote($quoteTransfer);

        $this->provider->removeItems($cartChangeTransfer);
    }

    /**
     * @dataProvider thereItemQuantitiesDataProvider
     *
     * @param int|float $existingItemQuantity
     * @param int|float $newFirstItemQuantity
     * @param int|float $newSecondItemQuantity
     *
     * @return void
     */
    public function testWithCustomCartAddItemStrategy(
        $existingItemQuantity,
        $newFirstItemQuantity,
        $newSecondItemQuantity
    ): void {
        $provider = new NonPersistentProvider([
            new CartItemAddTripleStrategy(),
        ], [
        ]);

        $existingItemId = '123';
        $newItemId = '321';

        $quoteTransfer = $this->createQuoteWithItem($existingItemId, $existingItemQuantity);

        $newFirstItem = $this->createItem($newItemId, $newFirstItemQuantity);
        $newSecondItem = $this->createItem($newItemId, $newSecondItemQuantity);
        $cartChangeTransfer = new CartChangeTransfer();
        $cartChangeTransfer->addItem($newFirstItem);
        $cartChangeTransfer->addItem($newSecondItem);
        $cartChangeTransfer->setQuote($quoteTransfer);

        $changedCart = $provider->addItems($cartChangeTransfer);
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
     * @param string $itemId
     * @param int $itemQuantity
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteWithItem($itemId, $itemQuantity)
    {
        $cart = $this->createQuoteTransfer();
        $existingItem = $this->createItem($itemId, $itemQuantity);
        $cart->addItem($existingItem);

        return $cart;
    }

    /**
     * @param string $itemId
     * @param int $itemQuantity
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function createItem($itemId, $itemQuantity)
    {
        $existingItem = new ItemTransfer();
        $existingItem->setId($itemId);
        $existingItem->setSku($itemId);
        $existingItem->setQuantity($itemQuantity);

        return $existingItem;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteTransfer()
    {
        return new QuoteTransfer();
    }
}
