<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Cart\Service\Zed;

use Generated\Shared\Cart\CartInterface;
use Generated\Shared\Cart\ChangeInterface;
use SprykerEngine\Shared\Transfer\TransferInterface;

interface CartStubInterface
{

    /**
     * @param ChangeInterface|TransferInterface $changeTransfer
     *
     * @return CartInterface
     */
    public function addItem(ChangeInterface $changeTransfer);

    /**
     * @param ChangeInterface|TransferInterface $changeTransfer
     *
     * @return CartInterface
     */
    public function removeItem(ChangeInterface $changeTransfer);

    /**
     * @param ChangeInterface|TransferInterface $changeTransfer
     *
     * @return CartInterface
     */
    public function increaseItemQuantity(ChangeInterface $changeTransfer);

    /**
     * @param ChangeInterface|TransferInterface $changeTransfer
     *
     * @return CartInterface
     */
    public function decreaseItemQuantity(ChangeInterface $changeTransfer);

    /**
     * @param ChangeInterface|TransferInterface $changeTransfer
     *
     * @return CartInterface
     */
    public function addCoupon(ChangeInterface $changeTransfer);

    /**
     * @param ChangeInterface|TransferInterface $changeTransfer
     *
     * @return CartInterface
     */
    public function removeCoupon(ChangeInterface $changeTransfer);

    /**
     * @param ChangeInterface|TransferInterface $changeTransfer
     *
     * @return CartInterface
     */
    public function clearCoupons(ChangeInterface $changeTransfer);

    /**
     * @param CartInterface|TransferInterface $cartTransfer
     *
     * @return CartInterface
     */
    public function recalculate(CartInterface $cartTransfer);

}
