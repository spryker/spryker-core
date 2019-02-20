<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\AgentQuoteRequest;

use Spryker\Client\AgentQuoteRequest\Dependency\Client\AgentQuoteRequestToZedRequestClientInterface;
use Spryker\Client\AgentQuoteRequest\QuoteRequest\QuoteRequestChecker;
use Spryker\Client\AgentQuoteRequest\QuoteRequest\QuoteRequestCheckerInterface;
use Spryker\Client\AgentQuoteRequest\Zed\AgentQuoteRequestStub;
use Spryker\Client\AgentQuoteRequest\Zed\AgentQuoteRequestStubInterface;
use Spryker\Client\Kernel\AbstractFactory;

/**
 * @method \Spryker\Client\AgentQuoteRequest\AgentQuoteRequestConfig getConfig()
 */
class AgentQuoteRequestFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\AgentQuoteRequest\QuoteRequest\QuoteRequestCheckerInterface
     */
    public function createQuoteRequestChecker(): QuoteRequestCheckerInterface
    {
        return new QuoteRequestChecker($this->getConfig());
    }

    /**
     * @return \Spryker\Client\AgentQuoteRequest\Zed\AgentQuoteRequestStubInterface
     */
    public function createAgentQuoteRequestStub(): AgentQuoteRequestStubInterface
    {
        return new AgentQuoteRequestStub($this->getZedRequestClient());
    }

    /**
     * @return \Spryker\Client\AgentQuoteRequest\Dependency\Client\AgentQuoteRequestToZedRequestClientInterface
     */
    public function getZedRequestClient(): AgentQuoteRequestToZedRequestClientInterface
    {
        return $this->getProvidedDependency(AgentQuoteRequestDependencyProvider::CLIENT_ZED_REQUEST);
    }
}
