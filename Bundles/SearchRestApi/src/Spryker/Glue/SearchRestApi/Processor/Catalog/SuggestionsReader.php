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
     * SuggestionsReader constructor.
     *
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

        $searchString = $this->suggestionsResourceMapper->mapRestSuggestionsRequestAttributesTransferToSuggestionsString($restRequest);
        $requestParameters = $this->suggestionsResourceMapper->mapRestSuggestionsRequestAttributesTransferToSuggestionsRequestParameters($restRequest);
        $restSuggestionsAttributeTransfer = $this->catalogClient->catalogSuggestSearch($searchString, $requestParameters);

        $restResource = $this->suggestionsResourceMapper->mapSuggestionsResponseAttributesTransferToRestResponse($restSuggestionsAttributeTransfer);

        return $response->addResource($restResource);
    }
}
