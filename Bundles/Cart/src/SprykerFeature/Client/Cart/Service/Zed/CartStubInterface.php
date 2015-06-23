<?php

namespace SprykerFeature\Client\Cart\Service\Zed;

use Generated\Shared\Cart\CartInterface;
use Generated\Shared\Cart\ChangeInterface;
use SprykerEngine\Shared\Transfer\TransferInterface;

interface CartStubInterface
{

    /**
     * @param ChangeInterface|TransferInterface $cartChange
     *
     * @return CartInterface
     */
    public function addItem(ChangeInterface $cartChange);

    /**
     * @param ChangeInterface|TransferInterface $cartChange
     *
     * @return CartInterface
     */
    public function removeItem(ChangeInterface $cartChange);

    /**
     * @param ChangeInterface|TransferInterface $cartChange
     *
     * @return CartInterface
     */
    public function increaseItemQuantity(ChangeInterface $cartChange);

    /**
     * @param ChangeInterface|TransferInterface $cartChange
     *
     * @return CartInterface
     */
    public function decreaseItemQuantity(ChangeInterface $cartChange);

    /**
     * @param ChangeInterface|TransferInterface $cartChange
     *
     * @return CartInterface
     */
    public function addCoupon(ChangeInterface $cartChange);

    /**
     * @param ChangeInterface|TransferInterface $cartChange
     *
     * @return CartInterface
     */
    public function removeCoupon(ChangeInterface $cartChange);

    /**
     * @param ChangeInterface|TransferInterface $cartChange
     *
     * @return CartInterface
     */
    public function clearCoupons(ChangeInterface $cartChange);

    /**
     * @param CartInterface|TransferInterface $cart
     *
     * @return CartInterface
     */
    public function recalculate(CartInterface $cart);

}
