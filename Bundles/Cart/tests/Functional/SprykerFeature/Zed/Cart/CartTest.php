<?php

namespace Functional\SprykerFeature\Zed\Cart;

use Codeception\TestCase\Test;
use Functional\SprykerFeature\Zed\Cart\Fixture\CartFacadeFixture;
use Generated\Shared\Transfer\Cart2CartTransfer;
use Generated\Shared\Transfer\CartCartItemTransfer;
use Generated\Zed\Ide\FactoryAutoCompletion\CartBusiness;
use SprykerEngine\Zed\Kernel\Business\Factory;
use SprykerEngine\Zed\Kernel\Locator;
use Generated\Shared\Transfer\CartCartChangeTransfer;
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
        parent::setUp();
        $this->locator = Locator::getInstance();
        /** @var CartBusiness $factory */
        $factory = new Factory('Cart');

        //use fixture here which wraps the original facade to override DI and Settings to not tests plugins
        $this->cartFacade = new CartFacadeFixture($factory, $this->locator);
    }

    /**
     * @group Zed
     * @group Business
     * @group Cart
     */
    public function testAddToCart()
    {
        $cart = new Cart2CartTransfer();
        $cartItem = new CartCartItemTransfer();
        $cartItem->setId('123');
        $cartItem->setQuantity(3);
        $cartItems = new CartCartItemTransfer();
        $cartItems->add($cartItem);
        $cart->setItems($cartItems);

        $newItems = new CartCartItemTransfer($this->locator);
        $newItem = new CartCartItemTransfer($this->locator);
        $newItem->setId('222');
        $newItem->setQuantity(1);
        $newItems->add($newItem);

        $cartChange = new CartCartChangeTransfer();
        $cartChange->setCart($cart);
        $cartChange->setChangedItems($newItems);

        $changedCart = $this->cartFacade->addToCart($cartChange);

        $this->assertCount(2, $changedCart->getItems());

        /** @var CartCartItemTransfer $item */
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
     * @group Zed
     * @group Business
     * @group Cart
     */
    public function testIncreaseCartQuantity()
    {
        $cart = new Cart2CartTransfer();
        $cartItem = new CartCartItemTransfer();
        $cartItem->setId('123');
        $cartItem->setQuantity(3);
        $cartItems = new CartCartItemTransfer();
        $cartItems->add($cartItem);
        $cart->setItems($cartItems);

        $newItems = new CartCartItemTransfer();
        $newItem = new CartCartItemTransfer();
        $newItem->setId('123');
        $newItem->setQuantity(1);
        $newItems->add($newItem);

        $cartChange = new CartCartChangeTransfer();
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
     * @group Zed
     * @group Business
     * @group Cart
     */
    public function testRemoveFromCart()
    {
        $cart = new Cart2CartTransfer();
        $cartItem = new CartCartItemTransfer();
        $cartItem->setId('222');
        $cartItem->setQuantity(1);
        $cartItems = new CartCartItemTransfer();
        $cartItems->add($cartItem);
        $cart->setItems($cartItems);

        $newItems = new CartCartItemTransfer();
        $newItem = new CartCartItemTransfer($this->locator);
        $newItem->setId('222');
        $newItem->setQuantity(1);
        $newItems->add($newItem);

        $cartChange = new CartCartChangeTransfer();
        $cartChange->setCart($cart);
        $cartChange->setChangedItems($newItems);

        $changedCart = $this->cartFacade->removeFromCart($cartChange);

        $this->assertCount(0, $changedCart->getItems());
        //@todo test recalculation
    }

    /**
     * @group Zed
     * @group Business
     * @group Cart
     */
    public function testDecreaseCartItem()
    {
        $cart = new Cart2CartTransfer($this->locator);
        $cartItem = new CartCartItemTransfer($this->locator);
        $cartItem->setId('123');
        $cartItem->setQuantity(3);
        $cartItems = new CartCartItemTransfer($this->locator);
        $cartItems->add($cartItem);
        $cart->setItems($cartItems);

        $newItems = new CartCartItemTransfer($this->locator);
        $newItem = new CartCartItemTransfer($this->locator);
        $newItem->setId('123');
        $newItem->setQuantity(1);
        $newItems->add($newItem);

        $cartChange = new CartCartChangeTransfer($this->locator);
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
