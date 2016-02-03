<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Cart\Zed;

use Generated\Shared\Transfer\CartTransfer;
use Generated\Shared\Transfer\ChangeTransfer;
use Spryker\Client\ZedRequest\Stub\BaseStub;

class CartStub extends BaseStub implements CartStubInterface
{

    /**
     * @param \Generated\Shared\Transfer\ChangeTransfer|\Spryker\Shared\Transfer\TransferInterface $changeTransfer
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function addItem(ChangeTransfer $changeTransfer)
    {
        return $this->zedStub->call('/cart/gateway/add-item', $changeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ChangeTransfer|\Spryker\Shared\Transfer\TransferInterface $changeTransfer
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function removeItem(ChangeTransfer $changeTransfer)
    {
        return $this->zedStub->call('/cart/gateway/remove-item', $changeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ChangeTransfer|\Spryker\Shared\Transfer\TransferInterface $changeTransfer
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function increaseItemQuantity(ChangeTransfer $changeTransfer)
    {
        return $this->zedStub->call('/cart/gateway/increase-item-quantity', $changeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ChangeTransfer|\Spryker\Shared\Transfer\TransferInterface $changeTransfer
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function decreaseItemQuantity(ChangeTransfer $changeTransfer)
    {
        return $this->zedStub->call('/cart/gateway/decrease-item-quantity', $changeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ChangeTransfer|\Spryker\Shared\Transfer\TransferInterface $changeTransfer
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function addCoupon(ChangeTransfer $changeTransfer)
    {
        return $this->zedStub->call('/cart/gateway/add-coupon-code', $changeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ChangeTransfer|\Spryker\Shared\Transfer\TransferInterface $changeTransfer
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function removeCoupon(ChangeTransfer $changeTransfer)
    {
        return $this->zedStub->call('/cart/gateway/remove-coupon-code', $changeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ChangeTransfer|\Spryker\Shared\Transfer\TransferInterface $changeTransfer
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function clearCoupons(ChangeTransfer $changeTransfer)
    {
        return $this->zedStub->call('/cart/gateway/clear-coupon-code', $changeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CartTransfer|\Spryker\Shared\Transfer\TransferInterface $cartTransfer
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function recalculate(CartTransfer $cartTransfer)
    {
        return $this->zedStub->call('/cart/gateway/recalculate', $cartTransfer);
    }

}
