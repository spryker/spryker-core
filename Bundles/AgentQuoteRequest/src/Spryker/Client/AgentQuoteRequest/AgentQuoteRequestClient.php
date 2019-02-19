<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\AgentQuoteRequest;

use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestOverviewCollectionTransfer;
use Generated\Shared\Transfer\QuoteRequestOverviewFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestResponseTransfer;
use Spryker\Client\AgentQuoteRequest\Zed\AgentQuoteRequestStubInterface;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\AgentQuoteRequest\AgentQuoteRequestFactory getFactory()
 */
class AgentQuoteRequestClient extends AbstractClient implements AgentQuoteRequestClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteRequestOverviewFilterTransfer $quoteRequestOverviewFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestOverviewCollectionTransfer
     */
    public function getQuoteRequestOverviewCollection(QuoteRequestOverviewFilterTransfer $quoteRequestOverviewFilterTransfer): QuoteRequestOverviewCollectionTransfer
    {
        return $this->getZedStub()->getQuoteRequestOverviewCollection($quoteRequestOverviewFilterTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteRequestFilterTransfer $quoteRequestFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function cancelByReference(QuoteRequestFilterTransfer $quoteRequestFilterTransfer): QuoteRequestResponseTransfer
    {
        return $this->getZedStub()->cancelByReference($quoteRequestFilterTransfer);
    }

    /**
     * @return \Spryker\Client\AgentQuoteRequest\Zed\AgentQuoteRequestStubInterface
     */
    protected function getZedStub(): AgentQuoteRequestStubInterface
    {
        return $this->getFactory()->createAgentQuoteRequestStub();
    }
}
