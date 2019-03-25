<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\AgentQuoteRequest\QuoteRequest;

use Generated\Shared\Transfer\QuoteRequestTransfer;
use Spryker\Client\AgentQuoteRequest\AgentQuoteRequestConfig;
use Spryker\Shared\AgentQuoteRequest\AgentQuoteRequestConfig as SharedAgentQuoteRequestConfig;

class QuoteRequestChecker implements QuoteRequestCheckerInterface
{
    /**
     * @var \Spryker\Client\AgentQuoteRequest\AgentQuoteRequestConfig
     */
    protected $agentQuoteRequestConfig;

    /**
     * @param \Spryker\Client\AgentQuoteRequest\AgentQuoteRequestConfig $agentQuoteRequestConfig
     */
    public function __construct(AgentQuoteRequestConfig $agentQuoteRequestConfig)
    {
        $this->agentQuoteRequestConfig = $agentQuoteRequestConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return bool
     */
    public function isQuoteRequestCancelable(QuoteRequestTransfer $quoteRequestTransfer): bool
    {
        return in_array($quoteRequestTransfer->getStatus(), $this->agentQuoteRequestConfig->getCancelableStatuses());
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return bool
     */
    public function isQuoteRequestRevisable(QuoteRequestTransfer $quoteRequestTransfer): bool
    {
        return $quoteRequestTransfer->getStatus() === SharedAgentQuoteRequestConfig::STATUS_WAITING
            || $quoteRequestTransfer->getStatus() === SharedAgentQuoteRequestConfig::STATUS_READY;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return bool
     */
    public function isQuoteRequestEditable(QuoteRequestTransfer $quoteRequestTransfer): bool
    {
        return $quoteRequestTransfer->getStatus() === SharedAgentQuoteRequestConfig::STATUS_IN_PROGRESS;
    }
}
