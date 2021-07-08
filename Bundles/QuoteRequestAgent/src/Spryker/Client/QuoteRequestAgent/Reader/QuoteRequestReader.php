<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteRequestAgent\Reader;

use Generated\Shared\Transfer\QuoteRequestCollectionTransfer;
use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Spryker\Client\QuoteRequestAgent\Dependency\Client\QuoteRequestAgentToQuoteRequestClientInterface;

class QuoteRequestReader implements QuoteRequestReaderInterface
{
    /**
     * @var \Spryker\Client\QuoteRequestAgent\Dependency\Client\QuoteRequestAgentToQuoteRequestClientInterface
     */
    protected $quoteRequestClient;

    /**
     * @param \Spryker\Client\QuoteRequestAgent\Dependency\Client\QuoteRequestAgentToQuoteRequestClientInterface $quoteRequestClient
     */
    public function __construct(QuoteRequestAgentToQuoteRequestClientInterface $quoteRequestClient)
    {
        $this->quoteRequestClient = $quoteRequestClient;
    }

    /**
     * @deprecated Use {@link \Spryker\Client\QuoteRequestAgent\Reader\QuoteRequestReaderInterface::findQuoteRequest()} instead.
     *
     * @param string $quoteRequestReference
     *
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer|null
     */
    public function findQuoteRequestByReference(string $quoteRequestReference): ?QuoteRequestTransfer
    {
        $quoteRequestFilterTransfer = (new QuoteRequestFilterTransfer())
            ->setQuoteRequestReference($quoteRequestReference)
            ->setWithHidden(true);

        return $this->quoteRequestClient
            ->getQuoteRequest($quoteRequestFilterTransfer)
            ->getQuoteRequest();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestFilterTransfer $quoteRequestFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer|null
     */
    public function findQuoteRequest(QuoteRequestFilterTransfer $quoteRequestFilterTransfer): ?QuoteRequestTransfer
    {
        $quoteRequestFilterTransfer = (new QuoteRequestFilterTransfer())
            ->fromArray($quoteRequestFilterTransfer->toArray(), true)
            ->setWithHidden(true);

        return $this->quoteRequestClient
            ->getQuoteRequest($quoteRequestFilterTransfer)
            ->getQuoteRequest();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestFilterTransfer $quoteRequestFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestCollectionTransfer
     */
    public function getQuoteRequestCollectionByFilter(QuoteRequestFilterTransfer $quoteRequestFilterTransfer): QuoteRequestCollectionTransfer
    {
        return $this->quoteRequestClient->getQuoteRequestCollectionByFilter($quoteRequestFilterTransfer);
    }
}
