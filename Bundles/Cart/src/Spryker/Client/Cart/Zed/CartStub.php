<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Cart\Zed;

use Generated\Shared\Transfer\CartChangeTransfer;
use Spryker\Client\ZedRequest\Stub\ZedRequestStub;

class CartStub extends ZedRequestStub implements CartStubInterface
{

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer|\Spryker\Shared\Transfer\TransferInterface $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addItem(CartChangeTransfer $cartChangeTransfer)
    {
        return $this->zedStub->call('/cart/gateway/add-item', $cartChangeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer|\Spryker\Shared\Transfer\TransferInterface $changeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function removeItem(CartChangeTransfer $changeTransfer)
    {
        return $this->zedStub->call('/cart/gateway/remove-item', $changeTransfer);
    }

}
