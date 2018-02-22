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
    const COUPON_CODE_1 = 'coupon code 1';
    const COUPON_CODE_2 = 'coupon code 2';

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
        $this->provider = new NonPersistentProvider();
    }

    //@todo test with more then 1 item

    /**
     * @return void
     */
    public function testAddExistingItem()
    {
        $itemId = '123';
        $existingQuantity = 1;
        $newQuantity = 3;

        $quoteTransfer = $this->createQuoteWithItem($itemId, $existingQuantity);
        $newItem = $this->createItem($itemId, $newQuantity);
        $change = new CartChangeTransfer();
        $change->addItem($newItem);
        $change->setQuote($quoteTransfer);

        $changedCart = $this->provider->addItems($change);
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
    public function testAddNewItem()
    {
        $itemId = '123';
        $newId = '321';
        $existingQuantity = 1;
        $newQuantity = 3;

        $quoteTransfer = $this->createQuoteWithItem($itemId, $existingQuantity);

        $newItem = $this->createItem($newId, $newQuantity);
        $change = new CartChangeTransfer();
        $change->addItem($newItem);
        $change->setQuote($quoteTransfer);

        $changedCart = $this->provider->addItems($change);
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
    public function testAddDoubleNewItem()
    {
        $existingItemId = '123';
        $newItemId = '321';
        $existingItemQuantity = 1;
        $newFirstItemQuantity = 3;
        $newSecondItemQuantity = 4;

        $quoteTransfer = $this->createQuoteWithItem($existingItemId, $existingItemQuantity);

        $newFirstItem = $this->createItem($newItemId, $newFirstItemQuantity);
        $newSecondItem = $this->createItem($newItemId, $newSecondItemQuantity);
        $change = new CartChangeTransfer();
        $change->addItem($newFirstItem);
        $change->addItem($newSecondItem);
        $change->setQuote($quoteTransfer);

        $changedCart = $this->provider->addItems($change);
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
    public function testRemoveExistingItem()
    {
        $itemId = '123';
        $existingQuantity = 1;
        $reduceQuantity = 1;

        $quoteTransfer = $this->createQuoteWithItem($itemId, $existingQuantity);
        $newItem = $this->createItem($itemId, $reduceQuantity);
        $change = new CartChangeTransfer();
        $change->addItem($newItem);
        $change->setQuote($quoteTransfer);

        $changedCart = $this->provider->removeItems($change);
        $this->assertCount(0, $changedCart->getItems());
    }

    /**
     * @return void
     */
    public function testRemoveNotExistingItem()
    {
        $itemId = '123';
        $existingQuantity = 1;
        $reduceQuantity = 1;
        $deleteItemId = '321';

        $quoteTransfer = $this->createQuoteWithItem($itemId, $existingQuantity);
        $newItem = $this->createItem($deleteItemId, $reduceQuantity);
        $change = new CartChangeTransfer();
        $change->addItem($newItem);
        $change->setQuote($quoteTransfer);

        $changedCart = $this->provider->removeItems($change);
        $changedItems = $changedCart->getItems();
        $this->assertCount(1, $changedItems);
        $item = $changedItems[0];
        $this->assertSame($itemId, $item->getId());
        $this->assertSame($existingQuantity, $item->getQuantity());
    }

    /**
     * @return void
     */
    public function testReduceWithMoreThenExists()
    {
        $itemId = '123';
        $existingQuantity = 1;
        $reduceQuantity = 3;

        $quoteTransfer = $this->createQuoteWithItem($itemId, $existingQuantity);
        $newItem = $this->createItem($itemId, $reduceQuantity);
        $change = new CartChangeTransfer();
        $change->addItem($newItem);
        $change->setQuote($quoteTransfer);

        $changedCart = $this->provider->removeItems($change);
        $this->assertCount(0, $changedCart->getItems());
    }

    /**
     * @expectedException \Spryker\Zed\Cart\Business\Exception\InvalidQuantityExeption
     * @expectedExceptionMessage Could not change cart item "123" with "-3" as value.
     *
     * @return void
     */
    public function testIncreaseWithNegativeValue()
    {
        $itemId = '123';
        $newId = '123';
        $existingQuantity = 1;
        $newQuantity = -3;

        $quoteTransfer = $this->createQuoteWithItem($itemId, $existingQuantity);

        $newItem = $this->createItem($newId, $newQuantity);
        $change = new CartChangeTransfer();
        $change->addItem($newItem);
        $change->setQuote($quoteTransfer);

        $this->provider->addItems($change);
    }

    /**
     * @expectedException \Spryker\Zed\Cart\Business\Exception\InvalidQuantityExeption
     * @expectedExceptionMessage Could not change cart item "123" with "0" as value.
     *
     * @return void
     */
    public function testIncreaseWithZeroValue()
    {
        $itemId = '123';
        $newId = '123';
        $existingQuantity = 1;
        $newQuantity = 0;

        $quoteTransfer = $this->createQuoteWithItem($itemId, $existingQuantity);

        $newItem = $this->createItem($newId, $newQuantity);
        $change = new CartChangeTransfer();
        $change->addItem($newItem);
        $change->setQuote($quoteTransfer);

        $this->provider->addItems($change);
    }

    /**
     * @expectedException \Spryker\Zed\Cart\Business\Exception\InvalidQuantityExeption
     * @expectedExceptionMessage Could not change cart item "123" with "-3" as value.
     *
     * @return void
     */
    public function testDecreaseWithNegativeValue()
    {
        $itemId = '123';
        $newId = '123';
        $existingQuantity = 1;
        $newQuantity = -3;

        $quoteTransfer = $this->createQuoteWithItem($itemId, $existingQuantity);

        $newItem = $this->createItem($newId, $newQuantity);
        $change = new CartChangeTransfer();
        $change->addItem($newItem);
        $change->setQuote($quoteTransfer);

        $this->provider->removeItems($change);
    }

    /**
     * @expectedException \Spryker\Zed\Cart\Business\Exception\InvalidQuantityExeption
     * @expectedExceptionMessage Could not change cart item "123" with "0" as value.
     *
     * @return void
     */
    public function testDecreaseWithZeroValue()
    {
        $itemId = '123';
        $newId = '123';
        $existingQuantity = 1;
        $newQuantity = 0;

        $quoteTransfer = $this->createQuoteWithItem($itemId, $existingQuantity);

        $newItem = $this->createItem($newId, $newQuantity);
        $change = new CartChangeTransfer();
        $change->addItem($newItem);
        $change->setQuote($quoteTransfer);

        $this->provider->removeItems($change);
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
     * @return \Generated\Shared\Transfer\ItemTransfer|\Generated\Shared\Transfer\ItemTransfer|\Spryker\Shared\Kernel\Transfer\AbstractTransfer
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
