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
    private $zedStub;

    /**
     * @param AbstractZedClient $zedStub
     */
    public function __construct(AbstractZedClient $zedStub)
    {
        $this->zedStub = $zedStub;
    }

    /**
     * @param ChangeInterface|TransferInterface $changeTransfer
     *
     * @return CartInterface
     */
    public function addItem(ChangeInterface $changeTransfer)
    {
        return $this->zedStub->call('/cart/gateway/add-item', $changeTransfer);
    }

    /**
     * @param ChangeInterface|TransferInterface $changeTransfer
     *
     * @return CartInterface
     */
    public function removeItem(ChangeInterface $changeTransfer)
    {
        return $this->zedStub->call('/cart/gateway/remove-item', $changeTransfer);
    }

    /**
     * @param ChangeInterface|TransferInterface $changeTransfer
     *
     * @return CartInterface
     */
    public function increaseItemQuantity(ChangeInterface $changeTransfer)
    {
        return $this->zedStub->call('/cart/gateway/increase-item-quantity', $changeTransfer);
    }

    /**
     * @param ChangeInterface|TransferInterface $changeTransfer
     *
     * @return CartInterface
     */
    public function decreaseItemQuantity(ChangeInterface $changeTransfer)
    {
        return $this->zedStub->call('/cart/gateway/decrease-item-quantity', $changeTransfer);
    }

    /**
     * @param ChangeInterface|TransferInterface $changeTransfer
     *
     * @return CartInterface
     */
    public function addCoupon(ChangeInterface $changeTransfer)
    {
        return $this->zedStub->call('/cart/gateway/add-coupon-code', $changeTransfer);
    }

    /**
     * @param ChangeInterface|TransferInterface $changeTransfer
     *
     * @return CartInterface
     */
    public function removeCoupon(ChangeInterface $changeTransfer)
    {
        return $this->zedStub->call('/cart/gateway/remove-coupon-code', $changeTransfer);
    }

    /**
     * @param ChangeInterface|TransferInterface $changeTransfer
     *
     * @return CartInterface
     */
    public function clearCoupons(ChangeInterface $changeTransfer)
    {
        return $this->zedStub->call('/cart/gateway/clear-coupon-code', $changeTransfer);
    }

    /**
     * @param CartInterface|TransferInterface $cartTransfer
     *
     * @return CartInterface
     */
    public function recalculate(CartInterface $cartTransfer)
    {
        return $this->zedStub->call('/cart/gateway/recalculate', $cartTransfer);
    }

}
