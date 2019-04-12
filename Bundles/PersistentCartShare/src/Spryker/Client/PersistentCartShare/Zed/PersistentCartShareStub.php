<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PersistentCartShare\Zed;

use Generated\Shared\Transfer\QuotePreviewRequestTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Spryker\Client\PersistentCartShare\Dependency\Client\PersistentCartShareToZedRequestClientInterface;

class PersistentCartShareStub implements PersistentCartShareStubInterface
{
    /**
     * @var \Spryker\Client\PersistentCartShare\Dependency\Client\PersistentCartShareToZedRequestClientInterface
     */
    protected $zedRequestClient;

    /**
     * @param \Spryker\Client\PersistentCartShare\Dependency\Client\PersistentCartShareToZedRequestClientInterface $zedRequestClient
     */
    public function __construct(PersistentCartShareToZedRequestClientInterface $zedRequestClient)
    {
        $this->zedRequestClient = $zedRequestClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuotePreviewRequestTransfer $quotePreviewRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function getQuoteForPreview(QuotePreviewRequestTransfer $quotePreviewRequestTransfer): QuoteResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer */
        $quoteResponseTransfer = $this->zedRequestClient->call('/persistent-cart-share/gateway/get-quote-for-preview', $quotePreviewRequestTransfer);

        return $quoteResponseTransfer;
    }
}
