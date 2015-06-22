<?php

namespace SprykerFeature\Client\Cart;

use Generated\Shared\Cart\CartInterface;
use Generated\Shared\PriceCartConnector\CartItemsInterface;
use Generated\Shared\Transfer\CartItemsTransfer;
use Generated\Shared\Transfer\CartItemTransfer;
use Generated\Shared\Transfer\CartTransfer;
use Generated\Shared\Transfer\ChangeTransfer;
use SprykerEngine\Client\Kernel\AbstractClient;
use SprykerFeature\Client\Cart\CartClientInterface;
use SprykerFeature\Client\Cart\Storage\CartStorageInterface;
use SprykerFeature\Client\Cart\Zed\CartStubInterface;

/**
 * @method CartDependencyContainer getDependencyContainer()
 */
class CartClient extends AbstractClient implements CartClientInterface
{

    /**
     * @var CartStubInterface
     */
    private $cartStub;

    /**
     * @return CartInterface
     */
    public function getCart()
    {
        return $this->getSession()->getCart();
    }

    /**
     * @return CartStorageInterface
     */
    private function getSession()
    {
        return $this->getDependencyContainer()->createSession();
    }

    /**
     * @return CartInterface
     */
    public function clearCart()
    {
        $cart = new CartTransfer();

        return $this->getSession()->setCart($cart);
    }

    /**
     * @return int
     */
    public function getItemCount()
    {

    }

    /**
     * @param string $sku
     * @param int $quantity
     *
     * @return CartInterface
     */
    public function addToCart($sku, $quantity = 1)
    {
        $addedItems = $this->createChangedItems($sku, $quantity);
        $cartChange = $this->prepareCartChange($addedItems);
        $this->cartStub->addItem($cartChange);

        return $this->handleCartResponse();
    }

    /**
     * @param string $sku
     *
     * @return CartInterface
     */
    public function removeFromCart($sku)
    {
        $cart = $this->getCart();

        if ($this->hasItemWithSku($sku)) {
            $deleteItem = $cart->getItems()->offsetGet($sku);
            $deletedItems = $this->createChangedItems($sku, $deleteItem->getQuantity());
            $cartChange = $this->prepareCartChange($deletedItems);
            $this->cartStub->removeItem($cartChange);

            return $this->handleCartResponse();
        }

        return $cart;
    }

    /**
     * @param string $sku
     * @param int $quantity
     *
     * @return CartInterface
     */
    public function decreaseItemQuantity($sku, $quantity = 1)
    {
        $cart = $this->getCart();

        if ($cart->getItems()->offsetExists($sku)) {
            $decreasedItems = $this->createChangedItems($sku, $quantity);
            $cartChange = $this->prepareCartChange($decreasedItems);
            $this->cartStub->call('/cart/gateway/decrease-item-quantity', $cartChange);

            return $this->handleCartResponse();
        }

        return $cart;
    }

    /**
     * @param string $sku
     * @param int $quantity
     *
     * @return CartInterface
     */
    public function increaseItemQuantity($sku, $quantity = 1)
    {
        $increasedItems = $this->createChangedItems($sku, $quantity);
        $cartChange = $this->prepareCartChange($increasedItems);
        $this->cartStub->increaseItemQuantity($cartChange);

        return $this->handleCartResponse();
    }

    /**
     * @return CartInterface
     */
    public function recalculate()
    {
        $cart = $this->getCart();
        $this->cartStub->recalculate($cart);

        return $this->handleCartResponse();
    }

    /**
     * @return ChangeTransfer
     */
    private function createCartChange()
    {
        $cart = $this->getCart();
        $cartChange = new ChangeTransfer();
        $cartChange->setCartHash($cart);

        return $cartChange;
    }

    /**
     * @param string $sku
     * @param int $quantity
     *
     * @return CartItemsTransfer
     */
    private function createChangedItems($sku, $quantity = 1)
    {
        $changedItem = new CartItemTransfer();
        $changedItem->setId($sku);
        $changedItem->setQuantity($quantity);

        $changedItems = new CartItemsTransfer();
        $changedItems->addCartItem($changedItem);

        return $changedItems;
    }

    /**
     * @param CartItemsInterface $changedItems
     *
     * @return ChangeTransfer
     */
    private function prepareCartChange(CartItemsInterface $changedItems)
    {
        $cartChange = $this->createCartChange();
        $cartChange->setChangedCartItems($changedItems);

        return $cartChange;
    }

    /**
     * @return CartInterface
     */
    private function handleCartResponse()
    {
        $cartResponse = $this->cartStub->getLastResponse();

        if (!$cartResponse->isSuccess()) {
            //@todo log errors

            return $this->getCart();
        }

        /** @var CartInterface $cart */
        $cart = $cartResponse->getTransfer();
        $this->session->setCart($cart);

        return $cart;
    }

}
