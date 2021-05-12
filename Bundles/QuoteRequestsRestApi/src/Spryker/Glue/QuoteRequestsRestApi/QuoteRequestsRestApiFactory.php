<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\QuoteRequestsRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\QuoteRequestsRestApi\Dependency\Client\QuoteRequestsRestApiToCartsRestApiClientInterface;
use Spryker\Glue\QuoteRequestsRestApi\Dependency\Client\QuoteRequestsRestApiToQuoteRequestClientInterface;
use Spryker\Glue\QuoteRequestsRestApi\Dependency\Service\QuoteRequestsRestApiToShipmentServiceInterface;
use Spryker\Glue\QuoteRequestsRestApi\Processor\Canceler\QuoteRequestCanceler;
use Spryker\Glue\QuoteRequestsRestApi\Processor\Canceler\QuoteRequestCancelerInterface;
use Spryker\Glue\QuoteRequestsRestApi\Processor\Creator\QuoteRequestCreator;
use Spryker\Glue\QuoteRequestsRestApi\Processor\Creator\QuoteRequestCreatorInterface;
use Spryker\Glue\QuoteRequestsRestApi\Processor\Mapper\QuoteRequestMapper;
use Spryker\Glue\QuoteRequestsRestApi\Processor\Mapper\QuoteRequestMapperInterface;
use Spryker\Glue\QuoteRequestsRestApi\Processor\Mapper\QuoteRequestsRequestMapper;
use Spryker\Glue\QuoteRequestsRestApi\Processor\Mapper\QuoteRequestsRequestMapperInterface;
use Spryker\Glue\QuoteRequestsRestApi\Processor\Reader\QuoteRequestReader;
use Spryker\Glue\QuoteRequestsRestApi\Processor\Reader\QuoteRequestReaderInterface;
use Spryker\Glue\QuoteRequestsRestApi\Processor\RestResponseBuilder\QuoteRequestsRestResponseBuilder;
use Spryker\Glue\QuoteRequestsRestApi\Processor\RestResponseBuilder\QuoteRequestsRestResponseBuilderInterface;

/**
 * @method \Spryker\Glue\QuoteRequestsRestApi\QuoteRequestsRestApiConfig getConfig()
 */
class QuoteRequestsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\QuoteRequestsRestApi\Processor\Mapper\QuoteRequestMapperInterface
     */
    public function createQuoteRequestMapper(): QuoteRequestMapperInterface
    {
        return new QuoteRequestMapper($this->getShipmentService());
    }

    /**
     * @return \Spryker\Glue\QuoteRequestsRestApi\Processor\Canceler\QuoteRequestCancelerInterface
     */
    public function createQuoteRequestCanceler(): QuoteRequestCancelerInterface
    {
        return new QuoteRequestCanceler(
            $this->getQuoteRequestClient(),
            $this->createQuoteRequestsRestResponseBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\QuoteRequestsRestApi\Processor\Reader\QuoteRequestReaderInterface
     */
    public function createQuoteRequestsReader(): QuoteRequestReaderInterface
    {
        return new QuoteRequestReader(
            $this->getQuoteRequestClient(),
            $this->createQuoteRequestsRestResponseBuilder(),
            $this->createQuoteRequestsRequestMapper()
        );
    }

    /**
     * @return \Spryker\Glue\QuoteRequestsRestApi\Processor\Mapper\QuoteRequestsRequestMapperInterface
     */
    public function createQuoteRequestsRequestMapper(): QuoteRequestsRequestMapperInterface
    {
        return new QuoteRequestsRequestMapper();
    }

    /**
     * @return \Spryker\Glue\QuoteRequestsRestApi\Processor\RestResponseBuilder\QuoteRequestsRestResponseBuilderInterface
     */
    public function createQuoteRequestsRestResponseBuilder(): QuoteRequestsRestResponseBuilderInterface
    {
        return new QuoteRequestsRestResponseBuilder(
            $this->getResourceBuilder(),
            $this->createQuoteRequestMapper(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Glue\QuoteRequestsRestApi\Processor\Creator\QuoteRequestCreatorInterface
     */
    public function createQuoteRequestCreator(): QuoteRequestCreatorInterface
    {
        return new QuoteRequestCreator(
            $this->getCartsRestApiClient(),
            $this->getQuoteRequestClient(),
            $this->createQuoteRequestsRestResponseBuilder(),
            $this->createQuoteRequestsRequestMapper()
        );
    }

    /**
     * @return \Spryker\Glue\QuoteRequestsRestApi\Dependency\Service\QuoteRequestsRestApiToShipmentServiceInterface
     */
    public function getShipmentService(): QuoteRequestsRestApiToShipmentServiceInterface
    {
        return $this->getProvidedDependency(QuoteRequestsRestApiDependencyProvider::SERVICE_SHIPMENT);
    }

    /**
     * @return \Spryker\Glue\QuoteRequestsRestApi\Dependency\Client\QuoteRequestsRestApiToCartsRestApiClientInterface
     */
    public function getCartsRestApiClient(): QuoteRequestsRestApiToCartsRestApiClientInterface
    {
        return $this->getProvidedDependency(QuoteRequestsRestApiDependencyProvider::CLIENT_CARTS_REST_API);
    }

    /**
     * @return \Spryker\Glue\QuoteRequestsRestApi\Dependency\Client\QuoteRequestsRestApiToQuoteRequestClientInterface
     */
    public function getQuoteRequestClient(): QuoteRequestsRestApiToQuoteRequestClientInterface
    {
        return $this->getProvidedDependency(QuoteRequestsRestApiDependencyProvider::CLIENT_QUOTE_REQUEST);
    }
}
