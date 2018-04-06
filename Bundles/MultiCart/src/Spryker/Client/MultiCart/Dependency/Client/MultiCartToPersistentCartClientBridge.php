<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MultiCart\Dependency\Client;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\QuoteUpdateRequestTransfer;

class MultiCartToPersistentCartClientBridge implements MultiCartToPersistentCartClientInterface
{
    /**
     * @var \Spryker\Client\PersistentCart\PersistentCartClientInterface
     */
    protected $persistentCartClient;

    /**
     * @param \Spryker\Client\PersistentCart\PersistentCartClientInterface $persistentCartClient
     */
    public function __construct($persistentCartClient)
    {
        $this->persistentCartClient = $persistentCartClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function createQuote(QuoteTransfer $quoteTransfer)
    {
        return $this->persistentCartClient->createQuote($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteUpdateRequestTransfer $quoteUpdateRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function updateQuote(QuoteUpdateRequestTransfer $quoteUpdateRequestTransfer)
    {
        return $this->persistentCartClient->updateQuote($quoteUpdateRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function deleteQuote(QuoteTransfer $quoteTransfer)
    {
        return $this->persistentCartClient->deleteQuote($quoteTransfer);
    }
}
