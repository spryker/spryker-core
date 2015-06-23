<?php

namespace SprykerFeature\Client\Cart\Service\Zed;

use Generated\Shared\Cart\CartInterface;
use Generated\Shared\Cart\ChangeInterface;
use SprykerEngine\Shared\Transfer\TransferInterface;
use SprykerFeature\Shared\ZedRequest\Client\AbstractZedClient;

class CartStub implements CartStubInterface
{

    /**
     * @var AbstractZedClient
     */
    private $stub;

    /**
     * @param AbstractZedClient $stub
     */
    public function __construct(AbstractZedClient $stub)
    {
        $this->stub = $stub;
    }

    /**
     * @param ChangeInterface|TransferInterface $cartChange
     *
     * @return CartInterface
     */
    public function addItem(ChangeInterface $cartChange)
    {
        return $this->stub->call('/cart/gateway/add-item', $cartChange);
    }

    /**
     * @param ChangeInterface|TransferInterface $cartChange
     *
     * @return CartInterface
     */
    public function removeItem(ChangeInterface $cartChange)
    {
        return $this->stub->call('/cart/gateway/remove-item', $cartChange);
    }

    /**
     * @param ChangeInterface|TransferInterface $cartChange
     *
     * @return CartInterface
     */
    public function increaseItemQuantity(ChangeInterface $cartChange)
    {
        return $this->stub->call('/cart/gateway/increase-item-quantity', $cartChange);
    }

    /**
     * @param ChangeInterface|TransferInterface $cartChange
     *
     * @return CartInterface
     */
    public function decreaseItemQuantity(ChangeInterface $cartChange)
    {
        return $this->stub->call('/cart/gateway/decrease-item-quantity', $cartChange);
    }

    /**
     * @param ChangeInterface|TransferInterface $cartChange
     *
     * @return CartInterface
     */
    public function addCoupon(ChangeInterface $cartChange)
    {
        return $this->stub->call('/cart/gateway/add-coupon-code', $cartChange);
    }

    /**
     * @param ChangeInterface|TransferInterface $cartChange
     *
     * @return CartInterface
     */
    public function removeCoupon(ChangeInterface $cartChange)
    {
        return $this->stub->call('/cart/gateway/remove-coupon-code', $cartChange);
    }

    /**
     * @param ChangeInterface|TransferInterface $cartChange
     *
     * @return CartInterface
     */
    public function clearCoupons(ChangeInterface $cartChange)
    {
        return $this->stub->call('/cart/gateway/clear-coupon-code', $cartChange);
    }

    /**
     * @param CartInterface|TransferInterface $cart
     *
     * @return CartInterface
     */
    public function recalculate(CartInterface $cart)
    {
        return $this->stub->call('/cart/gateway/recalculate', $cart);
    }

}
