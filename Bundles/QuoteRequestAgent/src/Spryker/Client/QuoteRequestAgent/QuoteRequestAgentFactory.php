<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteRequestAgent;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\QuoteRequestAgent\Converter\QuoteRequestAgentConverter;
use Spryker\Client\QuoteRequestAgent\Converter\QuoteRequestAgentConverterInterface;
use Spryker\Client\QuoteRequestAgent\Dependency\Client\QuoteRequestAgentToQuoteClientInterface;
use Spryker\Client\QuoteRequestAgent\Dependency\Client\QuoteRequestAgentToQuoteRequestClientInterface;
use Spryker\Client\QuoteRequestAgent\Dependency\Client\QuoteRequestAgentToZedRequestClientInterface;
use Spryker\Client\QuoteRequestAgent\Reader\QuoteRequestReader;
use Spryker\Client\QuoteRequestAgent\Reader\QuoteRequestReaderInterface;
use Spryker\Client\QuoteRequestAgent\Status\QuoteRequestAgentStatus;
use Spryker\Client\QuoteRequestAgent\Status\QuoteRequestAgentStatusInterface;
use Spryker\Client\QuoteRequestAgent\Zed\QuoteRequestAgentStub;
use Spryker\Client\QuoteRequestAgent\Zed\QuoteRequestAgentStubInterface;

/**
 * @method \Spryker\Client\QuoteRequestAgent\QuoteRequestAgentConfig getConfig()
 */
class QuoteRequestAgentFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\QuoteRequestAgent\Converter\QuoteRequestAgentConverterInterface
     */
    public function createQuoteRequestAgentConverter(): QuoteRequestAgentConverterInterface
    {
        return new QuoteRequestAgentConverter(
            $this->getQuoteClient(),
            $this->createQuoteRequestAgentStatus()
        );
    }

    /**
     * @return \Spryker\Client\QuoteRequestAgent\Status\QuoteRequestAgentStatusInterface
     */
    public function createQuoteRequestAgentStatus(): QuoteRequestAgentStatusInterface
    {
        return new QuoteRequestAgentStatus($this->getConfig());
    }

    /**
     * @return \Spryker\Client\QuoteRequestAgent\Reader\QuoteRequestReaderInterface
     */
    public function createQuoteRequestReader(): QuoteRequestReaderInterface
    {
        return new QuoteRequestReader(
            $this->getQuoteRequestClient()
        );
    }

    /**
     * @return \Spryker\Client\QuoteRequestAgent\Zed\QuoteRequestAgentStubInterface
     */
    public function createQuoteRequestAgentStub(): QuoteRequestAgentStubInterface
    {
        return new QuoteRequestAgentStub($this->getZedRequestClient());
    }

    /**
     * @return \Spryker\Client\QuoteRequestAgent\Dependency\Client\QuoteRequestAgentToZedRequestClientInterface
     */
    public function getZedRequestClient(): QuoteRequestAgentToZedRequestClientInterface
    {
        return $this->getProvidedDependency(QuoteRequestAgentDependencyProvider::CLIENT_ZED_REQUEST);
    }

    /**
     * @return \Spryker\Client\QuoteRequestAgent\Dependency\Client\QuoteRequestAgentToQuoteClientInterface
     */
    public function getQuoteClient(): QuoteRequestAgentToQuoteClientInterface
    {
        return $this->getProvidedDependency(QuoteRequestAgentDependencyProvider::CLIENT_QUOTE);
    }

    /**
     * @return \Spryker\Client\QuoteRequestAgent\Dependency\Client\QuoteRequestAgentToQuoteRequestClientInterface
     */
    public function getQuoteRequestClient(): QuoteRequestAgentToQuoteRequestClientInterface
    {
        return $this->getProvidedDependency(QuoteRequestAgentDependencyProvider::CLIENT_QUOTE_REQUEST);
    }
}
