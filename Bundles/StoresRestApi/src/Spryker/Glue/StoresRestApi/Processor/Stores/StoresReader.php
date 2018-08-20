<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\StoresRestApi\Processor\Stores;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\StoresRestApi\Processor\Mapper\StoresResourceMapperInterface;
use Spryker\Glue\StoresRestApi\StoresRestApiConfig;
use Spryker\Shared\Kernel\Store;

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
        $storesRestAttributes = $this->storesResourceMapper->mapStoreToStoresRestAttribute(
            $this->store,
            $this->countryReader->getStoresCountryAttributes($this->store->getCountries()),
            $this->currencyReader->getStoresCurrencyAttributes($this->store->getCurrencyIsoCodes())
        );

        $restResource = $this->restResourceBuilder->createRestResource(
            StoresRestApiConfig::RESOURCE_STORES,
            $this->store->getStoreName(),
            $storesRestAttributes
        );

        $response = $this->restResourceBuilder->createRestResponse();

        return $response->addResource($restResource);
    }
}
