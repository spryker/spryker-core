<?php

namespace Functional\SprykerFeature\Zed\Cart;

use Codeception\TestCase\Test;
use SprykerEngine\Zed\Kernel\Business\Factory;
use SprykerEngine\Zed\Kernel\Locator;
use Generated\Shared\Transfer\ChangeTransfer;
use Generated\Shared\Transfer\CartItemTransfer;
use Generated\Shared\Transfer\CartItemsTransfer;
use Generated\Shared\Transfer\CartTransfer;
use SprykerFeature\Zed\Cart\Business\CartFacade;

class CartTest extends Test
{
    /**
     * @var CartFacade
     */
    private $cartFacade;

    /**
     * @var Locator
     */
    private $locator;

    public function setUp()
    {
        // $this->markTestSkipped();
        parent::setUp();
        $this->locator = Locator::getInstance();

        $this->cartFacade = new CartFacade(
            new Factory('Cart'),
            $this->locator
        );
    }

    /**
     * @group Cart
     */
    public function testAddToCart()
    {
        $cart = new CartTransfer();
        $cartItem = new CartItemTransfer();
        $cartItem->setId('123');
        $cartItem->setQuantity(3);
        $cart->addItem($cartItem);

        $newItems = new CartItemsTransfer();
        // $newItems = new \ArrayObject();
        $newItem = new CartItemTransfer();
        $newItem->setId('222');
        $newItem->setQuantity(1);
        $newItems->addCartItem($newItem);
        // $newItems->append($newItem);

        $cartChange = new ChangeTransfer();
        // $cartChange->setCart($cart);
        $cartChange->setChangedCartItems($newItems);

        $changedCart = $this->cartFacade->addToCart($cartChange);

        $this->assertCount(2, $changedCart->getItems());

        /** @var CartItemTransfer $item */
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
        $cart = new CartTransfer();
        $cartItem = new CartItemTransfer();
        $cartItem->setId('123');
        $cartItem->setQuantity(3);
        $cart->addItem($cartItem);

        $newItems = new CartItemsTransfer();
        $newItem = new CartItemTransfer();
        $newItem->setId('123');
        $newItem->setQuantity(1);
        $newItems->addCartItem($newItem);

        $cartChange = new ChangeTransfer();
        // $cartChange->setCart($cart);
        $cartChange->setChangedCartItems($newItems);

        $changedCart = $this->cartFacade->increaseQuantity($cartChange);

        $this->assertCount(1, $changedCart->getItems());
        /** @var CartItemTransfer $item */
        $changedItem = $changedCart->getItems()->getFirstItem();
        $this->assertEquals(4, $changedItem->getQuantity());

        //@todo test recalculation
    }

    /**
     * @group Cart
     */
    public function testRemoveFromCart()
    {
        $cart = new CartTransfer();
        $cartItem = new CartItemTransfer();
        $cartItem->setId('222');
        $cartItem->setQuantity(1);
        $cart->addItem($cartItem);

        $newItems = new CartItemsTransfer();
        $newItem = new CartItemTransfer();
        $newItem->setId('222');
        $newItem->setQuantity(1);
        $newItems->addCartItem($newItem);

        $cartChange = new ChangeTransfer();
        // $cartChange->setCart($cart);
        $cartChange->setChangedCartItems($newItems);

        $changedCart = $this->cartFacade->removeFromCart($cartChange);

        $this->assertCount(0, $changedCart->getItems());
        //@todo test recalculation
    }

    /**
     * @group Cart
     */
    public function testDecreaseCartItem()
    {
        $cart = new CartTransfer();
        $cartItem = new CartItemTransfer();
        $cartItem->setId('123');
        $cartItem->setQuantity(3);
//        $cartItems = new ItemCollection();
//        $cartItems->add($cartItem);
        $cart->addItem($cartItem);

        $newItems = new CartItemsTransfer();
        $newItem = new CartItemTransfer();
        $newItem->setId('123');
        $newItem->setQuantity(1);
        $newItems->addCartItem($newItem);

        $cartChange = new ChangeTransfer();
        // $cartChange->setCart($cart);
        $cartChange->setChangedCartItems($newItems);

        $changedCart = $this->cartFacade->decreaseQuantity($cartChange);

        $this->assertCount(1, $changedCart->getItems());
        /** @var CartItemTransfer $item */
        $changedItem = $changedCart->getItems()->getFirstItem();
        $this->assertEquals(2, $changedItem->getQuantity());

        //@todo test recalculation
    }
}
