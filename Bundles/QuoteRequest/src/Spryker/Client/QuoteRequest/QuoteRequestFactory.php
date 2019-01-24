<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteRequest;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\QuoteRequest\Dependency\Client\QuoteRequestToZedRequestClientInterface;
use Spryker\Client\QuoteRequest\Zed\QuoteRequestStub;
use Spryker\Client\QuoteRequest\Zed\QuoteRequestStubInterface;

class QuoteRequestFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\QuoteRequest\Zed\QuoteRequestStubInterface
     */
    public function createQuoteRequestStub(): QuoteRequestStubInterface
    {
        return new QuoteRequestStub($this->getZedRequestClient());
    }

    /**
     * @return \Spryker\Client\QuoteRequest\Dependency\Client\QuoteRequestToZedRequestClientInterface
     */
    public function getZedRequestClient(): QuoteRequestToZedRequestClientInterface
    {
        return $this->getProvidedDependency(QuoteRequestDependencyProvider::CLIENT_ZED_REQUEST);
    }
}
