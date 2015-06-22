<?php

namespace SprykerFeature\Client\Cart\Zed;

use Generated\Shared\Cart\CartInterface;
use Generated\Shared\Cart\ChangeInterface;
use SprykerEngine\Client\Kernel\AbstractClient;
use SprykerEngine\Shared\Transfer\TransferInterface;
use SprykerFeature\Shared\ZedRequest\Client\AbstractZedClient;

/**
 * @method AbstractZedClient getStub()
 */
class CartClient extends AbstractClient implements CartStubInterface
{

    /**
     * @param ChangeInterface|TransferInterface $cartChange
     *
     * @return TransferInterface
     */
    public function addItem(ChangeInterface $cartChange)
    {
        return $this->getStub()->call('/cart/gateway/add-item', $cartChange);
    }

    /**
     * @param ChangeInterface|TransferInterface $cartChange
     *
     * @return TransferInterface
     */
    public function removeItem(ChangeInterface $cartChange)
    {
        return $this->getStub()->call('/cart/gateway/remove-item', $cartChange);
    }

    /**
     * @param ChangeInterface|TransferInterface $cartChange
     *
     * @return TransferInterface
     */
    public function increaseItemQuantity(ChangeInterface $cartChange)
    {
        return $this->getStub()->call('/cart/gateway/increase-item-quantity', $cartChange);
    }

    /**
     * @param ChangeInterface|TransferInterface $cartChange
     *
     * @return TransferInterface
     */
    public function decreaseItemQuantity(ChangeInterface $cartChange)
    {
        return $this->getStub()->call('/cart/gateway/decrease-item-quantity', $cartChange);
    }

    /**
     * @param ChangeInterface|TransferInterface $cartChange
     *
     * @return TransferInterface
     */
    public function addCoupon(ChangeInterface $cartChange)
    {
        return $this->getStub()->call('/cart/gateway/add-coupon-code', $cartChange);
    }

    /**
     * @param ChangeInterface|TransferInterface $cartChange
     *
     * @return TransferInterface
     */
    public function removeCoupon(ChangeInterface $cartChange)
    {
        return $this->getStub()->call('/cart/gateway/remove-coupon-code', $cartChange);
    }

    /**
     * @param ChangeInterface|TransferInterface $cartChange
     *
     * @return TransferInterface
     */
    public function clearCoupons(ChangeInterface $cartChange)
    {
        return $this->getStub()->call('/cart/gateway/clear-coupon-code', $cartChange);
    }

    /**
     * @param CartInterface|TransferInterface $cart
     *
     * @return mixed
     */
    public function recalculate(CartInterface $cart)
    {
        return $this->getStub()->call('/cart/gateway/recalculate', $cart);
    }

}
