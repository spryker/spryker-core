<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteRequest\QuoteRequest;

use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\QuoteRequest\Dependency\Client\QuoteRequestToPersistentCartClientInterface;
use Spryker\Client\QuoteRequest\Dependency\Client\QuoteRequestToQuoteClientInterface;

class QuoteRequestToQuoteConverter implements QuoteRequestToQuoteConverterInterface
{
    /**
     * @var \Spryker\Client\QuoteRequest\Dependency\Client\QuoteRequestToPersistentCartClientInterface
     */
    protected $persistentCartClient;

    /**
     * @var \Spryker\Client\QuoteRequest\Dependency\Client\QuoteRequestToQuoteClientInterface
     */
    protected $quoteClient;

    /**
     * @param \Spryker\Client\QuoteRequest\Dependency\Client\QuoteRequestToPersistentCartClientInterface $persistentCartClient
     * @param \Spryker\Client\QuoteRequest\Dependency\Client\QuoteRequestToQuoteClientInterface $quoteClient
     */
    public function __construct(QuoteRequestToPersistentCartClientInterface $persistentCartClient, QuoteRequestToQuoteClientInterface $quoteClient)
    {
        $this->persistentCartClient = $persistentCartClient;
        $this->quoteClient = $quoteClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function convertToQuote(QuoteRequestTransfer $quoteRequestTransfer): QuoteTransfer
    {
        $latestQuoteRequestVersionTransfer = $quoteRequestTransfer->getLatestVersion();
        $quoteTransfer = $latestQuoteRequestVersionTransfer->getQuote();

        $quoteTransfer->setQuoteRequestVersionReference($latestQuoteRequestVersionTransfer->getVersionReference());
        $this->quoteClient->lockQuote($quoteTransfer);

        return $this->persistentCartClient->replaceCustomerCart($quoteTransfer);
    }
}
