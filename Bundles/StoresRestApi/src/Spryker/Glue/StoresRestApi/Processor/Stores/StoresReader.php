<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\StoresRestApi\Processor\Stores;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\StoresRestApi\Dependency\Client\StoresRestApiToStoreClientInterface;
use Spryker\Glue\StoresRestApi\Processor\Mapper\StoresResourceMapperInterface;
use Spryker\Glue\StoresRestApi\StoresRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class StoresReader implements StoresReaderInterface
{
    /**
     * @var \Spryker\Glue\StoresRestApi\Processor\Stores\StoresCountryReaderInterface
     */
    protected $countryReader;

    /**
     * @var \Spryker\Glue\StoresRestApi\Processor\Stores\StoresCurrencyReaderInterface
     */
    protected $currencyReader;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\StoresRestApi\Processor\Mapper\StoresResourceMapperInterface $storesResourceMapper
     */
    protected $storesResourceMapper;

    /**
     * @var \Spryker\Glue\StoresRestApi\Dependency\Client\StoresRestApiToStoreClientInterface
     */
    protected $storeClient;

    /**
     * @param \Spryker\Glue\StoresRestApi\Processor\Stores\StoresCountryReaderInterface $countryReader
     * @param \Spryker\Glue\StoresRestApi\Processor\Stores\StoresCurrencyReaderInterface $currencyReader
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\StoresRestApi\Processor\Mapper\StoresResourceMapperInterface $storesResourceMapper
     * @param \Spryker\Glue\StoresRestApi\Dependency\Client\StoresRestApiToStoreClientInterface $storeClient
     */
    public function __construct(
        StoresCountryReaderInterface $countryReader,
        StoresCurrencyReaderInterface $currencyReader,
        RestResourceBuilderInterface $restResourceBuilder,
        StoresResourceMapperInterface $storesResourceMapper,
        StoresRestApiToStoreClientInterface $storeClient
    ) {
        $this->countryReader = $countryReader;
        $this->currencyReader = $currencyReader;
        $this->restResourceBuilder = $restResourceBuilder;
        $this->storesResourceMapper = $storesResourceMapper;
        $this->storeClient = $storeClient;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getStoresAttributes(RestRequestInterface $restRequest): RestResponseInterface
    {
        $currentStore = $this->storeClient->getCurrentStore();
        $response = $this->restResourceBuilder->createRestResponse();

        $storeId = $restRequest->getResource()->getId();
        if ($storeId && ($storeId !== $currentStore->getName())) {
            return $this->addInvalidStoreIdErrorToResponse($response);
        }

        $storesRestAttributes = $this->storesResourceMapper->mapStoreToStoresRestAttribute(
            $this->countryReader->getStoresCountryAttributes($currentStore->getCountries()),
            $this->currencyReader->getStoresCurrencyAttributes($currentStore->getAvailableCurrencyIsoCodes())
        );

        $restResource = $this->restResourceBuilder->createRestResource(
            StoresRestApiConfig::RESOURCE_STORES,
            $currentStore->getName(),
            $storesRestAttributes
        );

        return $response->addResource($restResource);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function addInvalidStoreIdErrorToResponse(RestResponseInterface $restResponse): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(StoresRestApiConfig::RESPONSE_CODE_STORE_NOT_FOUND)
            ->setStatus(Response::HTTP_NOT_FOUND)
            ->setDetail(StoresRestApiConfig::RESPONSE_MESSAGE_STORE_NOT_FOUND);

        return $restResponse->addError($restErrorTransfer);
    }
}
