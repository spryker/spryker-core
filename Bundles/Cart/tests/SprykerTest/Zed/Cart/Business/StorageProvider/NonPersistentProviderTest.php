<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Cart\Business\StorageProvider;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use PHPUnit_Framework_TestCase;
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
class NonPersistentProviderTest extends PHPUnit_Framework_TestCase
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

        /** @var \Generated\Shared\Transfer\ItemTransfer $changedItem */
        $changedItem = $changedItems[0];

        $this->assertEquals($itemId, $changedItem->getId());
        $this->assertEquals(
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
        /** @var \Generated\Shared\Transfer\ItemTransfer $cartItem */
        foreach ($changedItems as $key => $changedItem) {
            $skuIndex[$changedItem->getId()] = $key;
        }

        $this->assertArrayHasKey($itemId, $skuIndex);
        $this->assertArrayHasKey($newId, $skuIndex);

        /** @var \Generated\Shared\Transfer\ItemTransfer $addedItem */
        $addedItem = $changedItems[$skuIndex[$newId]];
        $this->assertEquals($newId, $addedItem->getId());
        $this->assertEquals($newQuantity, $addedItem->getQuantity());

        /** @var \Generated\Shared\Transfer\ItemTransfer $existingItem */
        $existingItem = $changedItems[$skuIndex[$itemId]];
        $this->assertEquals($itemId, $existingItem->getId());
        $this->assertEquals($existingQuantity, $existingItem->getQuantity());
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
        /** @var \Generated\Shared\Transfer\ItemTransfer $item */
        $item = $changedItems[0];
        $this->assertEquals($itemId, $item->getId());
        $this->assertEquals($existingQuantity, $item->getQuantity());
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
