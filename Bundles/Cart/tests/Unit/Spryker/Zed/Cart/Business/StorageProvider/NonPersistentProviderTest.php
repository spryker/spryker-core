<?php

namespace Unit\Spryker\Zed\Cart\Business\StorageProvider;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\CartChangeTransfer;
use Spryker\Shared\Transfer\AbstractTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Cart\Business\StorageProvider\NonPersistentProvider;
use Spryker\Zed\Cart\Business\StorageProvider\StorageProviderInterface;

/**
 * @group SprykerFeature
 * @group Zed
 * @group Cart
 * @group Business
 * @group InMemoryProvider
 */
class NonPersistentProviderTest extends \PHPUnit_Framework_TestCase
{

    const COUPON_CODE_1 = 'coupon code 1';
    const COUPON_CODE_2 = 'coupon code 2';

    /**
     * @var StorageProviderInterface
     */
    private $provider;

    protected function setUp()
    {
        parent::setUp();
        $this->provider = new NonPersistentProvider();
    }

    //@todo test with more then 1 item

    public function testAddExistingItem()
    {
        $quoteTransfer = $this->createQuoteWithItem($itemId = '123', $existingQuantity = 1);

        $newItem = $this->createItem($itemId, $newQuantity = 3);
        $change = new CartChangeTransfer();
        $change->addItem($newItem);
        $change->setQuote($quoteTransfer);

        $changedCart = $this->provider->addItems($change);
        $changedItems = $changedCart->getItems();
        $this->assertCount(2, $changedItems);

        /** @var ItemTransfer $changedItem */
        $changedItem = $changedItems[0];

        $this->assertEquals($itemId, $changedItem->getId());
        $this->assertEquals(
            1,
            $changedItem->getQuantity()
        );
    }

    public function testAddNewItem()
    {
        $quoteTransfer = $this->createQuoteWithItem($itemId = '123', $existingQuantity = 1);

        $newItem = $this->createItem($newId = '321', $newQuantity = 3);
        $change = new CartChangeTransfer();
        $change->addItem($newItem);
        $change->setQuote($quoteTransfer);

        $changedCart = $this->provider->addItems($change);
        $changedItems = $changedCart->getItems();
        $this->assertCount(2, $changedItems);

        $skuIndex = [];
        /* @var ItemTransfer $cartItem */
        foreach ($changedItems as $key => $changedItem) {
            $skuIndex[$changedItem->getId()] = $key;
        }

        $this->assertArrayHasKey($itemId, $skuIndex);
        $this->assertArrayHasKey($newId, $skuIndex);

        /** @var ItemTransfer $addedItem */
        $addedItem = $changedItems[$skuIndex[$newId]];
        $this->assertEquals($newId, $addedItem->getId());
        $this->assertEquals($newQuantity, $addedItem->getQuantity());

        /** @var ItemTransfer $existingItem */
        $existingItem = $changedItems[$skuIndex[$itemId]];
        $this->assertEquals($itemId, $existingItem->getId());
        $this->assertEquals($existingQuantity, $existingItem->getQuantity());
    }

    public function testRemoveExistingItem()
    {
        $quoteTransfer = $this->createQuoteWithItem($itemId = '123', $existingQuantity = 1);
        $newItem = $this->createItem($itemId, $reduceQuantity = 1);
        $change = new CartChangeTransfer();
        $change->addItem($newItem);
        $change->setQuote($quoteTransfer);

        $changedCart = $this->provider->removeItems($change);
        $this->assertCount(0, $changedCart->getItems());
    }

    public function testRemoveNotExistingItem()
    {
        $quoteTransfer = $this->createQuoteWithItem($itemId = '123', $existingQuantity = 1);
        $newItem = $this->createItem($deleteItemId = '321', $reduceQuantity = 1);
        $change = new CartChangeTransfer();
        $change->addItem($newItem);
        $change->setQuote($quoteTransfer);

        $changedCart = $this->provider->removeItems($change);
        $changedItems = $changedCart->getItems();
        $this->assertCount(1, $changedItems);
        /** @var ItemTransfer $item */
        $item = $changedItems[0];
        $this->assertEquals($itemId, $item->getId());
        $this->assertEquals($existingQuantity, $item->getQuantity());
    }

    public function testReduceWithMoreThenExists()
    {
        $quoteTransfer = $this->createQuoteWithItem($itemId = '123', $existingQuantity = 1);
        $newItem = $this->createItem($itemId, $reduceQuantity = 3);
        $change = new CartChangeTransfer();
        $change->addItem($newItem);
        $change->setQuote($quoteTransfer);

        $changedCart = $this->provider->removeItems($change);
        $this->assertCount(0, $changedCart->getItems());
    }

    /**
     * @expectedException \Spryker\Zed\Cart\Business\Exception\InvalidQuantityExeption
     * @expectedExceptionMessage Could not change cart item "123" with "-3" as value.
     */
    public function testIncreaseWithNegativeValue()
    {
        $quoteTransfer = $this->createQuoteWithItem($itemId = '123', $existingQuantity = 1);

        $newItem = $this->createItem($newId = '123', $newQuantity = -3);
        $change = new CartChangeTransfer();
        $change->addItem($newItem);
        $change->setQuote($quoteTransfer);

        $this->provider->addItems($change);
    }

    /**
     * @expectedException \Spryker\Zed\Cart\Business\Exception\InvalidQuantityExeption
     * @expectedExceptionMessage Could not change cart item "123" with "0" as value.
     */
    public function testIncreaseWithZeroValue()
    {
        $quoteTransfer = $this->createQuoteWithItem($itemId = '123', $existingQuantity = 1);

        $newItem = $this->createItem($newId = '123', $newQuantity = 0);
        $change = new CartChangeTransfer();
        $change->addItem($newItem);
        $change->setQuote($quoteTransfer);

        $this->provider->addItems($change);
    }

    /**
     * @expectedException \Spryker\Zed\Cart\Business\Exception\InvalidQuantityExeption
     * @expectedExceptionMessage Could not change cart item "123" with "-3" as value.
     */
    public function testDecreaseWithNegativeValue()
    {
        $quoteTransfer = $this->createQuoteWithItem($itemId = '123', $existingQuantity = 1);

        $newItem = $this->createItem($newId = '123', $newQuantity = -3);
        $change = new CartChangeTransfer();
        $change->addItem($newItem);
        $change->setQuote($quoteTransfer);

        $this->provider->removeItems($change);
    }

    /**
     * @expectedException \Spryker\Zed\Cart\Business\Exception\InvalidQuantityExeption
     * @expectedExceptionMessage Could not change cart item "123" with "0" as value.
     */
    public function testDecreaseWithZeroValue()
    {
        $quoteTransfer = $this->createQuoteWithItem($itemId = '123', $existingQuantity = 1);

        $newItem = $this->createItem($newId = '123', $newQuantity = 0);
        $change = new CartChangeTransfer();
        $change->addItem($newItem);
        $change->setQuote($quoteTransfer);

        $this->provider->removeItems($change);
    }

    /**
     * @param string $itemId
     * @param int $itemQuantity
     *
     * @return QuoteTransfer
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
     * @return ItemTransfer|ItemTransfer|AbstractTransfer
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
     * @return QuoteTransfer
     */
    protected function createQuoteTransfer()
    {
        return new QuoteTransfer();
    }

}
