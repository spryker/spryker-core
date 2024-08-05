<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\StoresRestApi\Processor\Stores;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Generated\Shared\Transfer\StoreCriteriaTransfer;
use Generated\Shared\Transfer\StoreTransfer;
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
     * @var \Spryker\Glue\StoresRestApi\Processor\Mapper\StoresResourceMapperInterface
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
        /**
         * Required by infrastructure, exists only for BC reasons with DMS mode.
         */
        if ($this->storeClient->isDynamicStoreEnabled()) {
            return $this->getDynamicStoreStoresAttributes($restRequest);
        }

        $currentStore = $this->storeClient->getCurrentStore();
        $storeId = $restRequest->getResource()->getId();

        if ($storeId && ($storeId !== $currentStore->getName())) {
            return $this->getInvalidStoreRestResponse();
        }

        return $this->getRestResponse($currentStore, $this->restResourceBuilder->createRestResponse());
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function getDynamicStoreStoresAttributes(RestRequestInterface $restRequest): RestResponseInterface
    {
        $response = $this->restResourceBuilder->createRestResponse();
        $storeId = $restRequest->getResource()->getId();

        if ($storeId !== null) {
            $storeTransfer = $this->storeClient->getStoreByName($storeId);

            if ($storeTransfer->getIdStore() === null) {
                return $this->getInvalidStoreRestResponse();
            }

            return $this->getRestResponse($storeTransfer, $response);
        }

        $storeCollectionTransfer = $this->storeClient->getStoreCollection(new StoreCriteriaTransfer());
        foreach ($storeCollectionTransfer->getStores() as $storeTransfer) {
            $response = $this->getRestResponse($storeTransfer, $response);
        }

        return $response;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function getRestResponse(
        StoreTransfer $storeTransfer,
        RestResponseInterface $restResponse
    ): RestResponseInterface {
        $restAttributesTransfer = $this->storesResourceMapper->mapStoreToStoresRestAttribute(
            $this->countryReader->getStoresCountryAttributes($storeTransfer->getCountries()),
            $this->currencyReader->getStoresCurrencyAttributes($storeTransfer->getAvailableCurrencyIsoCodes()),
        );

        /**
         * Required by infrastructure, exists only for BC reasons with DMS mode.
         */
        if ($this->storeClient->isDynamicStoreEnabled()) {
            $restAttributesTransfer = $this->storesResourceMapper->mapStoresRestAttributesTransferToDynamicStoreRestAttributesTransfer(
                $restAttributesTransfer,
            );
        }

        $restResource = $this->restResourceBuilder->createRestResource(
            StoresRestApiConfig::RESOURCE_STORES,
            $storeTransfer->getName(),
            $restAttributesTransfer,
        );

        return $restResponse->addResource($restResource);
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function getInvalidStoreRestResponse(): RestResponseInterface
    {
        return $this->addInvalidStoreIdErrorToResponse($this->restResourceBuilder->createRestResponse());
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
