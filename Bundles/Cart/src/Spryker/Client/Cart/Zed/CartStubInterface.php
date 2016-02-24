<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Cart\Zed;

use Generated\Shared\Transfer\CartChangeTransfer;

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

}
