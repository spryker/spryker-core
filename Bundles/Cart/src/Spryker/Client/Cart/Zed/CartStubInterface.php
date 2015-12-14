<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Cart\Zed;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\CartChangeTransfer;
use Spryker\Shared\Transfer\TransferInterface;

interface CartStubInterface
{

    /**
     * @param CartChangeTransfer|TransferInterface $cartChangeTransfer
     *
     * @return QuoteTransfer
     */
    public function addItem(CartChangeTransfer $cartChangeTransfer);

    /**
     * @param CartChangeTransfer|TransferInterface $cartChangeTransfer
     *
     * @return QuoteTransfer
     */
    public function removeItem(CartChangeTransfer $cartChangeTransfer);

    /**
     * @param CartChangeTransfer|TransferInterface $cartChangeTransfer
     *
     * @return QuoteTransfer
     */
    public function increaseItemQuantity(CartChangeTransfer $cartChangeTransfer);

    /**
     * @param CartChangeTransfer|TransferInterface $cartChangeTransfer
     *
     * @return QuoteTransfer
     */
    public function decreaseItemQuantity(CartChangeTransfer $cartChangeTransfer);

}
