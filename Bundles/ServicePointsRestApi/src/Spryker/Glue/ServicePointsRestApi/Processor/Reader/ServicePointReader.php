<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ServicePointsRestApi\Processor\Reader;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ServicePointsRestApi\Dependency\Client\ServicePointsRestApiToServicePointSearchClientInterface;
use Spryker\Glue\ServicePointsRestApi\Processor\Builder\ServicePointResponseBuilderInterface;
use Spryker\Glue\ServicePointsRestApi\Processor\Builder\ServicePointSearchRequestBuilderInterface;

class ServicePointReader implements ServicePointReaderInterface
{
    /**
     * @uses \Spryker\Client\ServicePointSearch\Plugin\Elasticsearch\ResultFormatter\ServicePointSearchResultFormatterPlugin::NAME
     *
     * @var string
     */
    protected const KEY_SERVICE_POINT_SEARCH_COLLECTION = 'ServicePointSearchCollection';

    /**
     * @var \Spryker\Glue\ServicePointsRestApi\Dependency\Client\ServicePointsRestApiToServicePointSearchClientInterface
     */
    protected ServicePointsRestApiToServicePointSearchClientInterface $servicePointSearchClient;

    /**
     * @var \Spryker\Glue\ServicePointsRestApi\Processor\Reader\ServicePointStorageReaderInterface
     */
    protected ServicePointStorageReaderInterface $servicePointStorageReader;

    /**
     * @var \Spryker\Glue\ServicePointsRestApi\Processor\Builder\ServicePointSearchRequestBuilderInterface
     */
    protected ServicePointSearchRequestBuilderInterface $servicePointSearchRequestBuilder;

    /**
     * @var \Spryker\Glue\ServicePointsRestApi\Processor\Builder\ServicePointResponseBuilderInterface
     */
    protected ServicePointResponseBuilderInterface $servicePointResponseBuilder;

    /**
     * @param \Spryker\Glue\ServicePointsRestApi\Dependency\Client\ServicePointsRestApiToServicePointSearchClientInterface $servicePointSearchClient
     * @param \Spryker\Glue\ServicePointsRestApi\Processor\Reader\ServicePointStorageReaderInterface $servicePointStorageReader
     * @param \Spryker\Glue\ServicePointsRestApi\Processor\Builder\ServicePointSearchRequestBuilderInterface $servicePointSearchRequestBuilder
     * @param \Spryker\Glue\ServicePointsRestApi\Processor\Builder\ServicePointResponseBuilderInterface $servicePointResponseBuilder
     */
    public function __construct(
        ServicePointsRestApiToServicePointSearchClientInterface $servicePointSearchClient,
        ServicePointStorageReaderInterface $servicePointStorageReader,
        ServicePointSearchRequestBuilderInterface $servicePointSearchRequestBuilder,
        ServicePointResponseBuilderInterface $servicePointResponseBuilder
    ) {
        $this->servicePointSearchClient = $servicePointSearchClient;
        $this->servicePointStorageReader = $servicePointStorageReader;
        $this->servicePointSearchRequestBuilder = $servicePointSearchRequestBuilder;
        $this->servicePointResponseBuilder = $servicePointResponseBuilder;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getServicePoint(RestRequestInterface $restRequest): RestResponseInterface
    {
        /** @var string $servicePointUuid */
        $servicePointUuid = $restRequest->getResource()->getId();
        $servicePointStorageTransfer = $this->servicePointStorageReader->findServicePointStorage($servicePointUuid);

        if (!$servicePointStorageTransfer) {
            return $this->servicePointResponseBuilder->createServicePointNotFoundErrorResponse($restRequest->getMetadata()->getLocale());
        }

        return $this->servicePointResponseBuilder->createServicePointRestResponse($servicePointStorageTransfer);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getServicePointCollection(RestRequestInterface $restRequest): RestResponseInterface
    {
        $servicePointSearchRequestTransfer = $this->servicePointSearchRequestBuilder->createServicePointSearchRequestTransfer($restRequest);

        $servicePointSearchCollectionTransfers = $this->servicePointSearchClient->searchServicePoints($servicePointSearchRequestTransfer);

        return $this->servicePointResponseBuilder->createServicePointCollectionRestResponse(
            $servicePointSearchCollectionTransfers[static::KEY_SERVICE_POINT_SEARCH_COLLECTION],
        );
    }
}
