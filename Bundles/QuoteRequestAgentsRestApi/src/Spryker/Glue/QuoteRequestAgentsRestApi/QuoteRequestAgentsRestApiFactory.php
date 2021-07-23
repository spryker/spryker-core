<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\QuoteRequestAgentsRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\QuoteRequestAgentsRestApi\Dependency\Client\QuoteRequestAgentsRestApiToCompanyUserStorageClientInterface;
use Spryker\Glue\QuoteRequestAgentsRestApi\Dependency\Client\QuoteRequestAgentsRestApiToQuoteRequestAgentClientInterface;
use Spryker\Glue\QuoteRequestAgentsRestApi\Dependency\RestResource\QuoteRequestAgentsRestApiToQuoteRequestsRestApiResourceInterface;
use Spryker\Glue\QuoteRequestAgentsRestApi\Processor\Creator\QuoteRequestCreator;
use Spryker\Glue\QuoteRequestAgentsRestApi\Processor\Creator\QuoteRequestCreatorInterface;
use Spryker\Glue\QuoteRequestAgentsRestApi\Processor\RestResponseBuilder\QuoteRequestRestResponseBuilder;
use Spryker\Glue\QuoteRequestAgentsRestApi\Processor\RestResponseBuilder\QuoteRequestRestResponseBuilderInterface;

/**
 * @method \Spryker\Glue\QuoteRequestAgentsRestApi\QuoteRequestAgentsRestApiConfig getConfig()
 */
class QuoteRequestAgentsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\QuoteRequestAgentsRestApi\Processor\Creator\QuoteRequestCreatorInterface
     */
    public function createQuoteRequestCreator(): QuoteRequestCreatorInterface
    {
        return new QuoteRequestCreator(
            $this->getQuoteRequestAgentClient(),
            $this->getCompanyUserStorageClient(),
            $this->getQuoteRequestsRestApiResource(),
            $this->createQuoteRequestRestResponseBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\QuoteRequestAgentsRestApi\Processor\RestResponseBuilder\QuoteRequestRestResponseBuilderInterface
     */
    public function createQuoteRequestRestResponseBuilder(): QuoteRequestRestResponseBuilderInterface
    {
        return new QuoteRequestRestResponseBuilder(
            $this->getResourceBuilder(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Glue\QuoteRequestAgentsRestApi\Dependency\RestResource\QuoteRequestAgentsRestApiToQuoteRequestsRestApiResourceInterface
     */
    public function getQuoteRequestsRestApiResource(): QuoteRequestAgentsRestApiToQuoteRequestsRestApiResourceInterface
    {
        return $this->getProvidedDependency(QuoteRequestAgentsRestApiDependencyProvider::RESOURCE_QUOTE_REQUESTS_REST_API);
    }

    /**
     * @return \Spryker\Glue\QuoteRequestAgentsRestApi\Dependency\Client\QuoteRequestAgentsRestApiToQuoteRequestAgentClientInterface
     */
    public function getQuoteRequestAgentClient(): QuoteRequestAgentsRestApiToQuoteRequestAgentClientInterface
    {
        return $this->getProvidedDependency(QuoteRequestAgentsRestApiDependencyProvider::CLIENT_QUOTE_REQUEST_AGENT);
    }

    /**
     * @return \Spryker\Glue\QuoteRequestAgentsRestApi\Dependency\Client\QuoteRequestAgentsRestApiToCompanyUserStorageClientInterface
     */
    public function getCompanyUserStorageClient(): QuoteRequestAgentsRestApiToCompanyUserStorageClientInterface
    {
        return $this->getProvidedDependency(QuoteRequestAgentsRestApiDependencyProvider::CLIENT_COMPANY_USER_STORAGE);
    }
}
