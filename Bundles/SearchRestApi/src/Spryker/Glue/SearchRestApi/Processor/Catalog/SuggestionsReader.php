<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SearchRestApi\Processor\Catalog;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\SearchRestApi\Dependency\Client\SearchRestApiToCatalogClientInterface;
use Spryker\Glue\SearchRestApi\Processor\Mapper\SuggestionsResourceMapperInterface;

class SuggestionsReader implements SuggestionsReaderInterface
{
    protected const QUERY_STRING_PARAMETER = 'q';

    /**
     * @var \Spryker\Glue\SearchRestApi\Dependency\Client\SearchRestApiToCatalogClientInterface
     */
    protected $catalogClient;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\SearchRestApi\Processor\Mapper\SuggestionsResourceMapperInterface
     */
    protected $suggestionsResourceMapper;

    /**
     * @param \Spryker\Glue\SearchRestApi\Dependency\Client\SearchRestApiToCatalogClientInterface $catalogClient
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\SearchRestApi\Processor\Mapper\SuggestionsResourceMapperInterface $suggestionsResourceMapper
     */
    public function __construct(
        SearchRestApiToCatalogClientInterface $catalogClient,
        RestResourceBuilderInterface $restResourceBuilder,
        SuggestionsResourceMapperInterface $suggestionsResourceMapper
    ) {
        $this->catalogClient = $catalogClient;
        $this->restResourceBuilder = $restResourceBuilder;
        $this->suggestionsResourceMapper = $suggestionsResourceMapper;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function catalogSuggestionsSearch(RestRequestInterface $restRequest): RestResponseInterface
    {
        $response = $this->restResourceBuilder->createRestResponse();

        $searchString = $this->getSuggestionsRestRequestQueryString($restRequest);

        if (empty($searchString)) {
            return $this->buildEmptyResponse($response);
        }

        $requestParameters = $this->getSuggestionsRestRequestAttributes($restRequest);
        $restSuggestionsAttributeTransfer = $this->catalogClient->catalogSuggestSearch($searchString, $requestParameters);

        $restResource = $this->suggestionsResourceMapper->mapSuggestionsResponseAttributesTransferToRestResponse($restSuggestionsAttributeTransfer);

        return $response->addResource($restResource);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return string
     */
    protected function getSuggestionsRestRequestQueryString(RestRequestInterface $restRequest): string
    {
        return $restRequest->getHttpRequest()->query->get(static::QUERY_STRING_PARAMETER, '');
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return array
     */
    protected function getSuggestionsRestRequestAttributes(RestRequestInterface $restRequest): array
    {
        return $restRequest->getHttpRequest()->query->all();
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $response
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function buildEmptyResponse(RestResponseInterface $response): RestResponseInterface
    {
        $resource = $this->suggestionsResourceMapper->mapSuggestionsResponseAttributesTransferToRestResponse([]);

        return $response->addResource($resource);
    }
}
