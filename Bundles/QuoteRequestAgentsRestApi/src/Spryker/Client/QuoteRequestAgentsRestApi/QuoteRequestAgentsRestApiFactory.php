<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteRequestAgentsRestApi;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\QuoteRequestAgentsRestApi\Dependency\Client\QuoteRequestAgentsRestApiToZedRequestClientInterface;
use Spryker\Client\QuoteRequestAgentsRestApi\Zed\QuoteRequestAgentsRestApiZedStub;
use Spryker\Client\QuoteRequestAgentsRestApi\Zed\QuoteRequestAgentsRestApiZedStubInterface;

class QuoteRequestAgentsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\QuoteRequestAgentsRestApi\Zed\QuoteRequestAgentsRestApiZedStubInterface
     */
    public function createQuoteRequestAgentsRestApiZedStub(): QuoteRequestAgentsRestApiZedStubInterface
    {
        return new QuoteRequestAgentsRestApiZedStub($this->getZedRequestClient());
    }

    /**
     * @return \Spryker\Client\QuoteRequestAgentsRestApi\Dependency\Client\QuoteRequestAgentsRestApiToZedRequestClientInterface
     */
    public function getZedRequestClient(): QuoteRequestAgentsRestApiToZedRequestClientInterface
    {
        return $this->getProvidedDependency(QuoteRequestAgentsRestApiDependencyProvider::CLIENT_ZED_REQUEST);
    }
}
