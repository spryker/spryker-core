<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CatalogSearchRestApi\Processor\Catalog;

use Generated\Shared\Transfer\PriceModeConfigurationTransfer;
use Spryker\Glue\CatalogSearchRestApi\CatalogSearchRestApiConfig;
use Spryker\Glue\CatalogSearchRestApi\Dependency\Client\CatalogSearchRestApiToCatalogClientInterface;
use Spryker\Glue\CatalogSearchRestApi\Dependency\Client\CatalogSearchRestApiToPriceClientInterface;
use Spryker\Glue\CatalogSearchRestApi\Processor\Mapper\CatalogSearchResourceMapperInterface;
use Spryker\Glue\CatalogSearchRestApi\Processor\Mapper\CatalogSearchSuggestionsResourceMapperInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Shared\Kernel\Store;

class CatalogSearchReader implements CatalogSearchReaderInterface
{
    /**
     * @var \Spryker\Glue\CatalogSearchRestApi\Dependency\Client\CatalogSearchRestApiToCatalogClientInterface
     */
    protected $catalogClient;

    /**
     * @var \Spryker\Glue\CatalogSearchRestApi\Dependency\Client\CatalogSearchRestApiToPriceClientInterface
     */
    protected $priceClient;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\CatalogSearchRestApi\Processor\Mapper\CatalogSearchResourceMapperInterface
     */
    protected $catalogSearchResourceMapper;

    /**
     * @var \Spryker\Glue\CatalogSearchRestApi\Processor\Mapper\CatalogSearchSuggestionsResourceMapperInterface
     */
    protected $catalogSearchSuggestionsResourceMapper;

    /**
     * @var \Spryker\Shared\Kernel\Store
     */
    protected $store;

    /**
     * @param \Spryker\Glue\CatalogSearchRestApi\Dependency\Client\CatalogSearchRestApiToCatalogClientInterface $catalogClient
     * @param \Spryker\Glue\CatalogSearchRestApi\Dependency\Client\CatalogSearchRestApiToPriceClientInterface $priceClient
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\CatalogSearchRestApi\Processor\Mapper\CatalogSearchResourceMapperInterface $catalogSearchResourceMapper
     * @param \Spryker\Glue\CatalogSearchRestApi\Processor\Mapper\CatalogSearchSuggestionsResourceMapperInterface $catalogSearchSuggestionsResourceMapper
     * @param \Spryker\Shared\Kernel\Store $store
     */
    public function __construct(
        CatalogSearchRestApiToCatalogClientInterface $catalogClient,
        CatalogSearchRestApiToPriceClientInterface $priceClient,
        RestResourceBuilderInterface $restResourceBuilder,
        CatalogSearchResourceMapperInterface $catalogSearchResourceMapper,
        CatalogSearchSuggestionsResourceMapperInterface $catalogSearchSuggestionsResourceMapper,
        Store $store
    ) {
        $this->catalogClient = $catalogClient;
        $this->priceClient = $priceClient;
        $this->restResourceBuilder = $restResourceBuilder;
        $this->catalogSearchResourceMapper = $catalogSearchResourceMapper;
        $this->catalogSearchSuggestionsResourceMapper = $catalogSearchSuggestionsResourceMapper;
        $this->store = $store;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function catalogSearch(RestRequestInterface $restRequest): RestResponseInterface
    {
        $response = $this->restResourceBuilder->createRestResponse();
        $searchString = $this->getRequestParameter($restRequest, CatalogSearchRestApiConfig::QUERY_STRING_PARAMETER);
        $requestParameters = $this->getAllRequestParameters($restRequest);
        $searchResult = $this->catalogClient->catalogSearch($searchString, $requestParameters);
        $restSearchAttributesTransfer = $this
            ->catalogSearchResourceMapper
            ->mapSearchResultToRestAttributesTransfer($searchResult, $this->store->getCurrencyIsoCode());

        $this->catalogSearchResourceMapper
            ->mapPrices($restSearchAttributesTransfer, $this->getPriceModeConfigurationTransfer());

        $restResource = $this->restResourceBuilder->createRestResource(
            CatalogSearchRestApiConfig::RESOURCE_CATALOG_SEARCH,
            null,
            $restSearchAttributesTransfer
        );

        return $response->addResource($restResource);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function catalogSuggestionsSearch(RestRequestInterface $restRequest): RestResponseInterface
    {
        $response = $this->restResourceBuilder->createRestResponse();
        $searchString = $this->getRequestParameter($restRequest, CatalogSearchRestApiConfig::QUERY_STRING_PARAMETER);
        if (empty($searchString)) {
            return $this->createEmptyResponse($response);
        }
        $requestParameters = $this->getAllRequestParameters($restRequest);
        $suggestions = $this->catalogClient->catalogSuggestSearch($searchString, $requestParameters);
        $restSuggestionsAttributesTransfer = $this
            ->catalogSearchSuggestionsResourceMapper
            ->mapSuggestionsToRestAttributesTransfer($suggestions, $this->store->getCurrencyIsoCode());

        $restResource = $this->restResourceBuilder->createRestResource(
            CatalogSearchRestApiConfig::RESOURCE_CATALOG_SEARCH_SUGGESTIONS,
            null,
            $restSuggestionsAttributesTransfer
        );

        return $response->addResource($restResource);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param string $parameterName
     *
     * @return string
     */
    protected function getRequestParameter(RestRequestInterface $restRequest, string $parameterName): string
    {
        return $restRequest->getHttpRequest()->query->get($parameterName, '');
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return array
     */
    protected function getAllRequestParameters(RestRequestInterface $restRequest): array
    {
        return $restRequest->getHttpRequest()->query->all();
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $response
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createEmptyResponse(RestResponseInterface $response): RestResponseInterface
    {
        $restSuggestionsAttributesTransfer = $this
            ->catalogSearchSuggestionsResourceMapper
            ->mapSuggestionsToRestAttributesTransfer(
                $this->catalogSearchSuggestionsResourceMapper->getEmptySearchResponse(),
                $this->store->getCurrencyIsoCode()
            );

        $restResource = $this->restResourceBuilder->createRestResource(
            CatalogSearchRestApiConfig::RESOURCE_CATALOG_SEARCH_SUGGESTIONS,
            null,
            $restSuggestionsAttributesTransfer
        );

        return $response->addResource($restResource);
    }

    /**
     * @return \Generated\Shared\Transfer\PriceModeConfigurationTransfer
     */
    protected function getPriceModeConfigurationTransfer(): PriceModeConfigurationTransfer
    {
        $priceModeConfiguration = new PriceModeConfigurationTransfer();

        $priceModeConfiguration->setCurrentPriceMode($this->priceClient->getCurrentPriceMode());
        $priceModeConfiguration->setGrossModeIdentifier($this->priceClient->getGrossPriceModeIdentifier());
        $priceModeConfiguration->setNetModeIdentifier($this->priceClient->getNetPriceModeIdentifier());

        return $priceModeConfiguration;
    }
}
