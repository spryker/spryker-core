<?php

namespace SprykerFeature\Client\Cart\Zed;

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
     * @return ChangeInterface
     */
    public function removeItem(ChangeInterface $cartChange);

    /**
     * @param ChangeInterface|TransferInterface $cartChange
     *
     * @return ChangeInterface
     */
    public function increaseItemQuantity(ChangeInterface $cartChange);

    /**
     * @param ChangeInterface|TransferInterface $cartChange
     *
     * @return ChangeInterface
     */
    public function decreaseItemQuantity(ChangeInterface $cartChange);

    /**
     * @param ChangeInterface|TransferInterface $cartChange
     *
     * @return ChangeInterface
     */
    public function addCoupon(ChangeInterface $cartChange);

    /**
     * @param ChangeInterface|TransferInterface $cartChange
     *
     * @return ChangeInterface
     */
    public function removeCoupon(ChangeInterface $cartChange);

    /**
     * @param ChangeInterface|TransferInterface $cartChange
     *
     * @return ChangeInterface
     */
    public function clearCoupon(ChangeInterface $cartChange);

    /**
     * @param CartInterface|TransferInterface $cart
     *
     * @return CartInterface
     */
    public function recalculate(CartInterface $cart);

}
