<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteRequest;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\QuoteRequest\Dependency\Client\QuoteRequestToPersistentCartClientInterface;
use Spryker\Client\QuoteRequest\Dependency\Client\QuoteRequestToQuoteClientInterface;
use Spryker\Client\QuoteRequest\Dependency\Client\QuoteRequestToZedRequestClientInterface;
use Spryker\Client\QuoteRequest\QuoteRequest\QuoteRequestChecker;
use Spryker\Client\QuoteRequest\QuoteRequest\QuoteRequestCheckerInterface;
use Spryker\Client\QuoteRequest\QuoteRequest\QuoteRequestToQuoteConverter;
use Spryker\Client\QuoteRequest\QuoteRequest\QuoteRequestToQuoteConverterInterface;
use Spryker\Client\QuoteRequest\QuoteRequest\QuoteRequestReader;
use Spryker\Client\QuoteRequest\QuoteRequest\QuoteRequestReaderInterface;
use Spryker\Client\QuoteRequest\Zed\QuoteRequestStub;
use Spryker\Client\QuoteRequest\Zed\QuoteRequestStubInterface;

/**
 * @method \Spryker\Client\QuoteRequest\QuoteRequestConfig getConfig()
 */
class QuoteRequestFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\QuoteRequest\QuoteRequest\QuoteRequestCheckerInterface
     */
    public function createQuoteRequestChecker(): QuoteRequestCheckerInterface
    {
        return new QuoteRequestChecker($this->getConfig());
    }

    /**
     * @return \Spryker\Client\QuoteRequest\QuoteRequest\QuoteRequestToQuoteConverterInterface
     */
    public function createQuoteRequestToQuoteConverter(): QuoteRequestToQuoteConverterInterface
    {
        return new QuoteRequestToQuoteConverter(
            $this->getPersistentCartClient(),
            $this->getQuoteClient(),
            $this->createQuoteRequestChecker()
        );
    }

    /**
     * @return \Spryker\Client\QuoteRequest\QuoteRequest\QuoteRequestReaderInterface
     */
    public function createQuoteRequestReader(): QuoteRequestReaderInterface
    {
        return new QuoteRequestReader($this->createQuoteRequestStub());
    }

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

    /**
     * @return \Spryker\Client\QuoteRequest\Dependency\Client\QuoteRequestToPersistentCartClientInterface
     */
    public function getPersistentCartClient(): QuoteRequestToPersistentCartClientInterface
    {
        return $this->getProvidedDependency(QuoteRequestDependencyProvider::CLIENT_PERSISTENT_CART);
    }

    /**
     * @return \Spryker\Client\QuoteRequest\Dependency\Client\QuoteRequestToQuoteClientInterface
     */
    public function getQuoteClient(): QuoteRequestToQuoteClientInterface
    {
        return $this->getProvidedDependency(QuoteRequestDependencyProvider::CLIENT_QUOTE);
    }
}
