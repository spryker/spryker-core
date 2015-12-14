<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Cart\Zed;

use Generated\Shared\Transfer\CartTransfer;
use Generated\Shared\Transfer\ChangeTransfer;
use SprykerEngine\Shared\Transfer\TransferInterface;
use SprykerFeature\Client\ZedRequest\Stub\BaseStub;

class CartStub extends BaseStub implements CartStubInterface
{

    /**
     * @param ChangeTransfer|TransferInterface $changeTransfer
     *
     * @return CartTransfer
     */
    public function addItem(ChangeTransfer $changeTransfer)
    {
        return $this->zedStub->call('/cart/gateway/add-item', $changeTransfer);
    }

    /**
     * @param ChangeTransfer|TransferInterface $changeTransfer
     *
     * @return CartTransfer
     */
    public function removeItem(ChangeTransfer $changeTransfer)
    {
        return $this->zedStub->call('/cart/gateway/remove-item', $changeTransfer);
    }

    /**
     * @param ChangeTransfer|TransferInterface $changeTransfer
     *
     * @return CartTransfer
     */
    public function increaseItemQuantity(ChangeTransfer $changeTransfer)
    {
        return $this->zedStub->call('/cart/gateway/increase-item-quantity', $changeTransfer);
    }

    /**
     * @param ChangeTransfer|TransferInterface $changeTransfer
     *
     * @return CartTransfer
     */
    public function decreaseItemQuantity(ChangeTransfer $changeTransfer)
    {
        return $this->zedStub->call('/cart/gateway/decrease-item-quantity', $changeTransfer);
    }

    /**
     * @param ChangeTransfer|TransferInterface $changeTransfer
     *
     * @return CartTransfer
     */
    public function addCoupon(ChangeTransfer $changeTransfer)
    {
        return $this->zedStub->call('/cart/gateway/add-coupon-code', $changeTransfer);
    }

    /**
     * @param ChangeTransfer|TransferInterface $changeTransfer
     *
     * @return CartTransfer
     */
    public function removeCoupon(ChangeTransfer $changeTransfer)
    {
        return $this->zedStub->call('/cart/gateway/remove-coupon-code', $changeTransfer);
    }

    /**
     * @param ChangeTransfer|TransferInterface $changeTransfer
     *
     * @return CartTransfer
     */
    public function clearCoupons(ChangeTransfer $changeTransfer)
    {
        return $this->zedStub->call('/cart/gateway/clear-coupon-code', $changeTransfer);
    }

    /**
     * @param CartTransfer|TransferInterface $cartTransfer
     *
     * @return CartTransfer
     */
    public function recalculate(CartTransfer $cartTransfer)
    {
        return $this->zedStub->call('/cart/gateway/recalculate', $cartTransfer);
    }

}
