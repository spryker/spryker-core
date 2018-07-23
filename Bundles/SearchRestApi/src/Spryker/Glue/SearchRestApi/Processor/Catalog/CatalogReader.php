<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SearchRestApi\Processor\Catalog;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\SearchRestApi\Dependency\Client\SearchRestApiToCatalogClientInterface;
use Spryker\Glue\SearchRestApi\Processor\Mapper\SearchResourceMapperInterface;
use Spryker\Glue\SearchRestApi\Processor\Mapper\SuggestionsResourceMapperInterface;
use Spryker\Glue\SearchRestApi\SearchRestApiConfig;
use Spryker\Shared\Kernel\Store;
use Symfony\Component\HttpFoundation\Response;

class CatalogReader implements CatalogReaderInterface
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
     * @var \Spryker\Glue\SearchRestApi\Processor\Mapper\SearchResourceMapperInterface
     */
    protected $searchResourceMapper;

    /**
     * @var \Spryker\Glue\SearchRestApi\Processor\Mapper\SuggestionsResourceMapperInterface
     */
    protected $suggestionsResourceMapper;

    /**
     * @var \Spryker\Shared\Kernel\Store
     */
    protected $store;

    /**
     * @param \Spryker\Glue\SearchRestApi\Dependency\Client\SearchRestApiToCatalogClientInterface $catalogClient
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\SearchRestApi\Processor\Mapper\SearchResourceMapperInterface $searchResourceMapper
     * @param \Spryker\Glue\SearchRestApi\Processor\Mapper\SuggestionsResourceMapperInterface $suggestionsResourceMapper
     * @param \Spryker\Shared\Kernel\Store $store
     */
    public function __construct(
        SearchRestApiToCatalogClientInterface $catalogClient,
        RestResourceBuilderInterface $restResourceBuilder,
        SearchResourceMapperInterface $searchResourceMapper,
        SuggestionsResourceMapperInterface $suggestionsResourceMapper,
        Store $store
    ) {
        $this->catalogClient = $catalogClient;
        $this->restResourceBuilder = $restResourceBuilder;
        $this->searchResourceMapper = $searchResourceMapper;
        $this->suggestionsResourceMapper = $suggestionsResourceMapper;
        $this->store = $store;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function catalogSearch(RestRequestInterface $restRequest): RestResponseInterface
    {
        $currency = $this->getCurrency($restRequest);
        if (!$this->isCurrencyAvailable($currency)) {
            return $this->createInvalidCurrencyResponse();
        }
        $this->store->setCurrencyIsoCode($currency);

        $response = $this->restResourceBuilder->createRestResponse();
        $searchString = $this->getRequestParameter($restRequest, SearchRestApiConfig::QUERY_STRING_PARAMETER);
        $requestParameters = $this->getAllRequestParameters($restRequest);
        $restSearchResponseAttributesTransfer = $this->catalogClient->catalogSearch($searchString, $requestParameters);
        $restResource = $this->searchResourceMapper->mapSearchResponseAttributesTransferToRestResponse($restSearchResponseAttributesTransfer);

        return $response->addResource($restResource);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function catalogSuggestionsSearch(RestRequestInterface $restRequest): RestResponseInterface
    {
        $currency = $this->getCurrency($restRequest);
        if (!$this->isCurrencyAvailable($currency)) {
            return $this->createInvalidCurrencyResponse();
        }
        $this->store->setCurrencyIsoCode($currency);

        $response = $this->restResourceBuilder->createRestResponse();
        $searchString = $this->getRequestParameter($restRequest, SearchRestApiConfig::QUERY_STRING_PARAMETER);
        if (empty($searchString)) {
            return $this->createEmptyResponse($response);
        }
        $requestParameters = $this->getAllRequestParameters($restRequest);
        $restSuggestionsAttributeTransfer = $this->catalogClient->catalogSuggestSearch($searchString, $requestParameters);
        $restResource = $this->suggestionsResourceMapper->mapSuggestionsResponseAttributesTransferToRestResponse($restSuggestionsAttributeTransfer);

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
        $resource = $this->suggestionsResourceMapper->mapSuggestionsResponseAttributesTransferToRestResponse(
            $this->suggestionsResourceMapper->getEmptySearchResponse()
        );

        return $response->addResource($resource);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return string
     */
    protected function getCurrency($restRequest): string
    {
        $currency = $this->getRequestParameter($restRequest, SearchRestApiConfig::CURRENCY_STRING_PARAMETER);
        if (empty($currency)) {
            return $this->store->getDefaultCurrencyCode();
        }

        return $currency;
    }

    /**
     * @param string $currency
     *
     * @return bool
     */
    protected function isCurrencyAvailable(string $currency): bool
    {
        return in_array($currency, $this->store->getCurrencyIsoCodes());
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createInvalidCurrencyResponse(): RestResponseInterface
    {
        $response = $this->restResourceBuilder->createRestResponse();

        return $response->addError((new RestErrorMessageTransfer())
            ->setCode(SearchRestApiConfig::RESPONSE_CODE_INVALID_CURRENCY)
            ->setStatus(Response::HTTP_BAD_REQUEST)
            ->setDetail(SearchRestApiConfig::RESPONSE_DETAIL_INVALID_CURRENCY));
    }
}
