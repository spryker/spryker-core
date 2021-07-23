<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteRequestsRestApi;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\QuoteRequestsRestApi\Dependency\Client\QuoteRequestsRestApiToZedRequestClientInterface;
use Spryker\Client\QuoteRequestsRestApi\Zed\QuoteRequestsRestApiZedStub;
use Spryker\Client\QuoteRequestsRestApi\Zed\QuoteRequestsRestApiZedStubInterface;

class QuoteRequestsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\QuoteRequestsRestApi\Zed\QuoteRequestsRestApiZedStubInterface
     */
    public function createQuoteRequestsRestApiZedStub(): QuoteRequestsRestApiZedStubInterface
    {
        return new QuoteRequestsRestApiZedStub($this->getZedRequestClient());
    }

    /**
     * @return \Spryker\Client\QuoteRequestsRestApi\Dependency\Client\QuoteRequestsRestApiToZedRequestClientInterface
     */
    public function getZedRequestClient(): QuoteRequestsRestApiToZedRequestClientInterface
    {
        return $this->getProvidedDependency(QuoteRequestsRestApiDependencyProvider::CLIENT_ZED_REQUEST);
    }
}
