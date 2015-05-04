<?php

namespace Functional\SprykerFeature\Zed\Cart2;

use Codeception\TestCase\Test;
use SprykerEngine\Zed\Kernel\Business\Factory;
use SprykerEngine\Zed\Kernel\Locator;
use Generated\Shared\Transfer\Cart2CartTransfer;
use Generated\Shared\Transfer\Cart2CartChangeTransfer;
use Generated\Shared\Transfer\Cart2ItemTransfer;
use SprykerFeature\Zed\Cart2\Business\Cart2Facade;

class Cart2Test extends Test
{
    /**
     * @var Cart2Facade
     */
    private $cartFacade;

    /**
     * @var Locator
     */
    private $locator;

    public function setUp()
    {
        $this->markTestSkipped();
        parent::setUp();
        $this->locator = Locator::getInstance();

        $this->cartFacade = new Cart2Facade(
            new Factory('Cart2'),
            $this->locator
        );
    }

    /**
     * @group Cart
     */
    public function testAddToCart()
    {
        $cart = new Cart($this->locator);
        $cartItem = new Item($this->locator);
        $cartItem->setId('123');
        $cartItem->setQuantity(3);
        $cartItems = new ItemCollection($this->locator);
        $cartItems->add($cartItem);
        $cart->setItems($cartItems);

        $newItems = new ItemCollection($this->locator);
        $newItem = new Item($this->locator);
        $newItem->setId('222');
        $newItem->setQuantity(1);
        $newItems->add($newItem);

        $cartChange = new CartChange($this->locator);
        $cartChange->setCart($cart);
        $cartChange->setChangedItems($newItems);

        $changedCart = $this->cartFacade->addToCart($cartChange);

        $this->assertCount(2, $changedCart->getItems());

        /** @var Item $item */
        foreach ($cart->getItems() as $item) {
            if ($item->getId() === $cartItem->getId()) {
                $this->assertEquals($cartItem->getQuantity(), $item->getQuantity());
            } elseif ($newItem->getId() === $item->getId()) {
                $this->assertEquals($newItem->getQuantity(), $item->getQuantity());
            } else {
                $this->fail('Cart has a unknown item inside');
            }
        }
    }

    /**
     * @group Cart
     */
    public function testIncreaseCartQuantity()
    {
        $cart = new Cart($this->locator);
        $cartItem = new Item($this->locator);
        $cartItem->setId('123');
        $cartItem->setQuantity(3);
        $cartItems = new ItemCollection($this->locator);
        $cartItems->add($cartItem);
        $cart->setItems($cartItems);

        $newItems = new ItemCollection($this->locator);
        $newItem = new Item($this->locator);
        $newItem->setId('123');
        $newItem->setQuantity(1);
        $newItems->add($newItem);

        $cartChange = new CartChange($this->locator);
        $cartChange->setCart($cart);
        $cartChange->setChangedItems($newItems);

        $changedCart = $this->cartFacade->increaseQuantity($cartChange);

        $this->assertCount(1, $changedCart->getItems());
        /** @var Item $changedItem */
        $changedItem = $changedCart->getItems()->getFirstItem();
        $this->assertEquals(4, $changedItem->getQuantity());

        //@todo test recalculation
    }

    /**
     * @group Cart
     */
    public function testRemoveFromCart()
    {
        $cart = new Cart($this->locator);
        $cartItem = new Item($this->locator);
        $cartItem->setId('222');
        $cartItem->setQuantity(1);
        $cartItems = new ItemCollection($this->locator);
        $cartItems->add($cartItem);
        $cart->setItems($cartItems);

        $newItems = new ItemCollection($this->locator);
        $newItem = new Item($this->locator);
        $newItem->setId('222');
        $newItem->setQuantity(1);
        $newItems->add($newItem);

        $cartChange = new CartChange($this->locator);
        $cartChange->setCart($cart);
        $cartChange->setChangedItems($newItems);

        $changedCart = $this->cartFacade->removeFromCart($cartChange);

        $this->assertCount(0, $changedCart->getItems());
        //@todo test recalculation
    }

    /**
     * @group Cart
     */
    public function testDecreaseCartItem()
    {
        $cart = new Cart($this->locator);
        $cartItem = new Item($this->locator);
        $cartItem->setId('123');
        $cartItem->setQuantity(3);
        $cartItems = new ItemCollection($this->locator);
        $cartItems->add($cartItem);
        $cart->setItems($cartItems);

        $newItems = new ItemCollection($this->locator);
        $newItem = new Item($this->locator);
        $newItem->setId('123');
        $newItem->setQuantity(1);
        $newItems->add($newItem);

        $cartChange = new CartChange($this->locator);
        $cartChange->setCart($cart);
        $cartChange->setChangedItems($newItems);

        $changedCart = $this->cartFacade->decreaseQuantity($cartChange);

        $this->assertCount(1, $changedCart->getItems());
        /** @var Item $changedItem */
        $changedItem = $changedCart->getItems()->getFirstItem();
        $this->assertEquals(2, $changedItem->getQuantity());

        //@todo test recalculation
    }
}
