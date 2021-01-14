<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\QuoteRequestsRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\QuoteRequestsRestApi\Dependency\QuoteRequestsRestApiToShipmentServiceInterface;
use Spryker\Glue\QuoteRequestsRestApi\Processor\Creator\QuoteRequestCreator;
use Spryker\Glue\QuoteRequestsRestApi\Processor\Creator\QuoteRequestCreatorInterface;
use Spryker\Glue\QuoteRequestsRestApi\Processor\Mapper\ErrorMapper;
use Spryker\Glue\QuoteRequestsRestApi\Processor\Mapper\ErrorMapperInterface;
use Spryker\Glue\QuoteRequestsRestApi\Processor\Mapper\QuoteRequestMapper;
use Spryker\Glue\QuoteRequestsRestApi\Processor\Mapper\QuoteRequestMapperInterface;
use Spryker\Glue\QuoteRequestsRestApi\Processor\Mapper\QuoteRequestsRequestMapper;
use Spryker\Glue\QuoteRequestsRestApi\Processor\Mapper\QuoteRequestsRequestMapperInterface;
use Spryker\Glue\QuoteRequestsRestApi\Processor\RestResponseBuilder\QuoteRequestsRestResponseBuilder;
use Spryker\Glue\QuoteRequestsRestApi\Processor\RestResponseBuilder\QuoteRequestsRestResponseBuilderInterface;

/**
 * @method \Spryker\Client\QuoteRequestsRestApi\QuoteRequestsRestApiClientInterface getClient()
 */
class QuoteRequestsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\QuoteRequestsRestApi\Processor\Mapper\QuoteRequestMapperInterface
     */
    public function createQuoteRequestMapper(): QuoteRequestMapperInterface
    {
        return new QuoteRequestMapper(
            $this->getShipmentService(),
            $this->getRestQuoteRequestsItemExpanderPlugins()
        );
    }

    /**
     * @return \Spryker\Glue\QuoteRequestsRestApi\Processor\Mapper\ErrorMapperInterface
     */
    public function createErrorMapper(): ErrorMapperInterface
    {
        return new ErrorMapper();
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
            $this->createErrorMapper()
        );
    }

    /**
     * @return \Spryker\Glue\QuoteRequestsRestApi\Processor\Creator\QuoteRequestCreatorInterface
     */
    public function createQuoteRequestCreator(): QuoteRequestCreatorInterface
    {
        return new QuoteRequestCreator(
            $this->getClient(),
            $this->createQuoteRequestsRestResponseBuilder(),
            $this->createQuoteRequestsRequestMapper()
        );
    }

    /**
     * @return \Spryker\Glue\QuoteRequestsRestApi\Dependency\QuoteRequestsRestApiToShipmentServiceInterface
     */
    public function getShipmentService(): QuoteRequestsRestApiToShipmentServiceInterface
    {
        return $this->getProvidedDependency(QuoteRequestsRestApiDependencyProvider::SERVICE_SHIPMENT);
    }

    /**
     * @return \Spryker\Glue\QuoteRequestsRestApiExtension\Dependency\Plugin\RestQuoteRequestsItemExpanderPluginInterface[]
     */
    public function getRestQuoteRequestsItemExpanderPlugins(): array
    {
        return $this->getProvidedDependency(QuoteRequestsRestApiDependencyProvider::PLUGINS_REST_QUOTE_REQUEST_ITEM_EXPANDER);
    }
}
