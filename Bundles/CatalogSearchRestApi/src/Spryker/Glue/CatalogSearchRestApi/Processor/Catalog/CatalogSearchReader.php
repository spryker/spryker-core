<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CatalogSearchRestApi\Processor\Catalog;

use Generated\Shared\Transfer\PriceModeConfigurationTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\CatalogSearchRestApi\CatalogSearchRestApiConfig;
use Spryker\Glue\CatalogSearchRestApi\Dependency\Client\CatalogSearchRestApiToCatalogClientInterface;
use Spryker\Glue\CatalogSearchRestApi\Dependency\Client\CatalogSearchRestApiToPriceClientInterface;
use Spryker\Glue\CatalogSearchRestApi\Processor\Mapper\CatalogSearchResourceMapperInterface;
use Spryker\Glue\CatalogSearchRestApi\Processor\Mapper\CatalogSearchSuggestionsResourceMapperInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Shared\Kernel\Store;
use Symfony\Component\HttpFoundation\Response;

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
        $currency = $this->getCurrency($restRequest);
        if (!$this->isCurrencyAvailable($currency)) {
            return $this->createInvalidCurrencyResponse();
        }
        $this->store->setCurrencyIsoCode($currency);

        $response = $this->restResourceBuilder->createRestResponse();
        $searchString = $this->getRequestParameter($restRequest, CatalogSearchRestApiConfig::QUERY_STRING_PARAMETER);
        $requestParameters = $this->getAllRequestParameters($restRequest);
        $restSearchResponseAttributesTransfer = $this->catalogClient->catalogSearch($searchString, $requestParameters);
        $restSearchAttributesTransfer = $this
            ->catalogSearchResourceMapper
            ->mapSearchResponseAttributesTransferToRestAttributesTransfer($restSearchResponseAttributesTransfer, $currency);
        $priceModeInformation = $this->getPriceModeInformation();
        $this->catalogSearchResourceMapper->mapPrices($restSearchAttributesTransfer, $priceModeInformation);

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
        $currency = $this->getCurrency($restRequest);
        if (!$this->isCurrencyAvailable($currency)) {
            return $this->createInvalidCurrencyResponse();
        }
        $this->store->setCurrencyIsoCode($currency);

        $response = $this->restResourceBuilder->createRestResponse();
        $searchString = $this->getRequestParameter($restRequest, CatalogSearchRestApiConfig::QUERY_STRING_PARAMETER);
        if (empty($searchString)) {
            return $this->createEmptyResponse($response, $currency);
        }
        $requestParameters = $this->getAllRequestParameters($restRequest);
        $restSuggestionsAttributeTransfer = $this->catalogClient->catalogSuggestSearch($searchString, $requestParameters);
        $restSuggestionsAttributesTransfer = $this
            ->catalogSearchSuggestionsResourceMapper
            ->mapSuggestionsResponseAttributesTransferToRestAttributesTransfer($restSuggestionsAttributeTransfer, $currency);

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
     * @param string $currency
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createEmptyResponse(RestResponseInterface $response, string $currency): RestResponseInterface
    {
        $restSuggestionsAttributesTransfer = $this
            ->catalogSearchSuggestionsResourceMapper
            ->mapSuggestionsResponseAttributesTransferToRestAttributesTransfer(
                $this->catalogSearchSuggestionsResourceMapper->getEmptySearchResponse(),
                $currency
            );

        $restResource = $this->restResourceBuilder->createRestResource(
            CatalogSearchRestApiConfig::RESOURCE_CATALOG_SEARCH_SUGGESTIONS,
            null,
            $restSuggestionsAttributesTransfer
        );

        return $response->addResource($restResource);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return string
     */
    protected function getCurrency($restRequest): string
    {
        $currency = $this->getRequestParameter($restRequest, CatalogSearchRestApiConfig::CURRENCY_STRING_PARAMETER);
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
            ->setCode(CatalogSearchRestApiConfig::RESPONSE_CODE_INVALID_CURRENCY)
            ->setStatus(Response::HTTP_BAD_REQUEST)
            ->setDetail(CatalogSearchRestApiConfig::RESPONSE_DETAIL_INVALID_CURRENCY));
    }

    /**
     * @return \Generated\Shared\Transfer\PriceModeConfigurationTransfer
     */
    protected function getPriceModeInformation(): PriceModeConfigurationTransfer
    {
        $priceModeConfiguration = new PriceModeConfigurationTransfer();

        $priceModeConfiguration->setCurrentPriceMode($this->priceClient->getCurrentPriceMode());
        $priceModeConfiguration->setGrossModeIdentifier($this->priceClient->getGrossPriceModeIdentifier());
        $priceModeConfiguration->setNetModeIdentifier($this->priceClient->getNetPriceModeIdentifier());

        return $priceModeConfiguration;
    }
}
