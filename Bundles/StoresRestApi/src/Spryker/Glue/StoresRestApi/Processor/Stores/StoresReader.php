<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\StoresRestApi\Processor\Stores;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Generated\Shared\Transfer\StoresRestAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\StoresRestApi\Processor\Mapper\StoresResourceMapperInterface;
use Spryker\Glue\StoresRestApi\StoresRestApiConfig;
use Spryker\Shared\Kernel\Store;
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
     * @var \Spryker\Shared\Kernel\Store
     */
    protected $store;

    /**
     * @param \Spryker\Glue\StoresRestApi\Processor\Stores\StoresCountryReaderInterface $countryReader
     * @param \Spryker\Glue\StoresRestApi\Processor\Stores\StoresCurrencyReaderInterface $currencyReader
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\StoresRestApi\Processor\Mapper\StoresResourceMapperInterface $storesResourceMapper
     * @param \Spryker\Shared\Kernel\Store $store
     */
    public function __construct(
        StoresCountryReaderInterface $countryReader,
        StoresCurrencyReaderInterface $currencyReader,
        RestResourceBuilderInterface $restResourceBuilder,
        StoresResourceMapperInterface $storesResourceMapper,
        Store $store
    ) {
        $this->countryReader = $countryReader;
        $this->currencyReader = $currencyReader;
        $this->restResourceBuilder = $restResourceBuilder;
        $this->storesResourceMapper = $storesResourceMapper;
        $this->store = $store;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getStoresAttributes(RestRequestInterface $restRequest): RestResponseInterface
    {
        if (!$this->store->getStoreName()) {
            return $this->createInvalidStoreResponse();
        }

        $storesRestAttributes = new StoresRestAttributesTransfer();

        foreach ($this->store->getLocales() as $identifier => $name) {
            $storesRestAttributes = $this->storesResourceMapper->mapLocaleToStoresRestAttributes(
                $storesRestAttributes,
                $identifier,
                $name
            );
        }

        foreach ($this->store->getCountries() as $iso2Code) {
            $storesRestAttributes = $this->storesResourceMapper->mapStoreCountryToStoresRestAttributes(
                $storesRestAttributes,
                $this->countryReader->getStoresCountryAttributes($iso2Code)
            );
        }

        $storesRestAttributes = isset($this->store->getContexts()['*']['timezone']) ?
            $this->storesResourceMapper->mapTimeZoneToStoresRestAttributes(
                $storesRestAttributes,
                $this->store->getContexts()['*']['timezone']
            ) : $storesRestAttributes;

        $storesCurrencyAttributes = $this->currencyReader->getStoresCurrencyAttributes($this->store->getCurrencyIsoCode());
        $storesRestAttributes = $this->storesResourceMapper->mapStoreCurrencyToStoresRestAttributes(
            $storesRestAttributes,
            $storesCurrencyAttributes
        );

        $restResource = $this->restResourceBuilder->createRestResource(
            StoresRestApiConfig::RESOURCE_STORES,
            $this->store->getStoreName(),
            $storesRestAttributes
        );

        $response = $this->restResourceBuilder->createRestResponse();

        return $response->addResource($restResource);
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createInvalidStoreResponse(): RestResponseInterface
    {
        return $this->restResourceBuilder->createRestResponse()->addError((new RestErrorMessageTransfer())
            ->setCode(StoresRestApiConfig::RESPONSE_CODE_CANT_FIND_STORE)
            ->setStatus(Response::HTTP_BAD_REQUEST)
            ->setDetail(StoresRestApiConfig::RESPONSE_DETAIL_CANT_FIND_STORE));
    }
}
