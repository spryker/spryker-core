<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\AgentQuoteRequest;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\AgentQuoteRequest\Dependency\Client\AgentQuoteRequestToZedRequestClientInterface;
use Spryker\Client\AgentQuoteRequest\Zed\AgentQuoteRequestStub;
use Spryker\Client\AgentQuoteRequest\Zed\AgentQuoteRequestStubInterface;

class AgentQuoteRequestFactory extends AbstractFactory
{
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
