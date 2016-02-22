<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Cart\Zed;

use Generated\Shared\Transfer\CartTransfer;
use Generated\Shared\Transfer\ChangeTransfer;

interface CartStubInterface
{

    /**
     * @param \Generated\Shared\Transfer\ChangeTransfer|\Spryker\Shared\Transfer\TransferInterface $changeTransfer
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function addItem(ChangeTransfer $changeTransfer);

    /**
     * @param \Generated\Shared\Transfer\ChangeTransfer|\Spryker\Shared\Transfer\TransferInterface $changeTransfer
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function removeItem(ChangeTransfer $changeTransfer);

    /**
     * @param \Generated\Shared\Transfer\ChangeTransfer|\Spryker\Shared\Transfer\TransferInterface $changeTransfer
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function increaseItemQuantity(ChangeTransfer $changeTransfer);

    /**
     * @param \Generated\Shared\Transfer\ChangeTransfer|\Spryker\Shared\Transfer\TransferInterface $changeTransfer
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function decreaseItemQuantity(ChangeTransfer $changeTransfer);

    /**
     * @param \Generated\Shared\Transfer\ChangeTransfer|\Spryker\Shared\Transfer\TransferInterface $changeTransfer
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function addCoupon(ChangeTransfer $changeTransfer);

    /**
     * @param \Generated\Shared\Transfer\ChangeTransfer|\Spryker\Shared\Transfer\TransferInterface $changeTransfer
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function removeCoupon(ChangeTransfer $changeTransfer);

    /**
     * @param \Generated\Shared\Transfer\ChangeTransfer|\Spryker\Shared\Transfer\TransferInterface $changeTransfer
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function clearCoupons(ChangeTransfer $changeTransfer);

    /**
     * @param \Generated\Shared\Transfer\CartTransfer|\Spryker\Shared\Transfer\TransferInterface $cartTransfer
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function recalculate(CartTransfer $cartTransfer);

}
