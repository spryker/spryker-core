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
     * @param \Generated\Shared\Transfer\CartChangeTransfer|\Spryker\Shared\Transfer\TransferInterface $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addItem(CartChangeTransfer $cartChangeTransfer);

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer|\Spryker\Shared\Transfer\TransferInterface $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function removeItem(CartChangeTransfer $cartChangeTransfer);

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer|\Spryker\Shared\Transfer\TransferInterface $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function increaseItemQuantity(CartChangeTransfer $cartChangeTransfer);

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer|\Spryker\Shared\Transfer\TransferInterface $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function decreaseItemQuantity(CartChangeTransfer $cartChangeTransfer);

}
