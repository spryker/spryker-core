<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CartReorder\Creator;

use Generated\Shared\Transfer\CartReorderRequestTransfer;
use Generated\Shared\Transfer\CartReorderResponseTransfer;
use Spryker\Client\CartReorder\Dependency\Client\CartReorderToQuoteClientInterface;
use Spryker\Client\CartReorder\Zed\CartReorderStubInterface;

class CartReorderCreator implements CartReorderCreatorInterface
{
    /**
     * @var \Spryker\Client\CartReorder\Zed\CartReorderStubInterface
     */
    protected CartReorderStubInterface $cartReorderStub;

    /**
     * @var \Spryker\Client\CartReorder\Dependency\Client\CartReorderToQuoteClientInterface
     */
    protected CartReorderToQuoteClientInterface $quoteClient;

    /**
     * @param \Spryker\Client\CartReorder\Zed\CartReorderStubInterface $cartReorderStub
     * @param \Spryker\Client\CartReorder\Dependency\Client\CartReorderToQuoteClientInterface $quoteClient
     */
    public function __construct(
        CartReorderStubInterface $cartReorderStub,
        CartReorderToQuoteClientInterface $quoteClient
    ) {
        $this->cartReorderStub = $cartReorderStub;
        $this->quoteClient = $quoteClient;
    }

    /**
     * @param \Generated\Shared\Transfer\CartReorderRequestTransfer $cartReorderRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CartReorderResponseTransfer
     */
    public function reorder(CartReorderRequestTransfer $cartReorderRequestTransfer): CartReorderResponseTransfer
    {
        $quoteTransfer = $this->quoteClient->getQuote();

        $cartReorderRequestTransfer->setQuote($quoteTransfer);
        $cartReorderResponseTransfer = $this->cartReorderStub->reorder($cartReorderRequestTransfer);

        if (!$cartReorderResponseTransfer->getErrors()->count()) {
            $this->quoteClient->setQuote($cartReorderResponseTransfer->getQuoteOrFail());
        }

        return $cartReorderResponseTransfer;
    }
}
