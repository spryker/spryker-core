<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\StoresRestApi\Processor\Stores;

use Generated\Shared\Transfer\CountryTransfer;
use Generated\Shared\Transfer\RegionCollectionTransfer;
use Generated\Shared\Transfer\StoreCountryRestAttributesTransfer;
use Generated\Shared\Transfer\StoreRegionRestAttributesTransfer;
use Spryker\Glue\StoresRestApi\Dependency\Client\StoresRestApiToCountryClientInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\StoresRestApi\Processor\Mapper\StoresCountryResourceMapperInterface;
use Spryker\Glue\StoresRestApi\StoresRestApiConfig;
use Spryker\Shared\Kernel\Store;
use Symfony\Component\HttpFoundation\Response;

class StoresCountryReader implements StoresCountryReaderInterface
{
    /**
     * @var \Spryker\Glue\StoresRestApi\Dependency\Client\StoresRestApiToCountryClientInterface
     */
    protected $countryClient;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\StoresRestApi\Processor\Mapper\StoresCountryResourceMapperInterface
     */
    protected $storesCountryResourceMapper;

    /**
     * @var \Spryker\Shared\Kernel\Store
     */
    protected $store;

    /**
     * @param \Spryker\Glue\StoresRestApi\Dependency\Client\StoresRestApiToCountryClientInterface $countryClient
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\StoresRestApi\Processor\Mapper\StoresCountryResourceMapperInterface $countryResourceMapper
     */
    public function __construct(
        StoresRestApiToCountryClientInterface $countryClient,
        RestResourceBuilderInterface $restResourceBuilder,
        StoresCountryResourceMapperInterface $storesCountryResourceMapper,
        $store
    ) {
        $this->countryClient = $countryClient;
        $this->restResourceBuilder = $restResourceBuilder;
        $this->storesCountryResourceMapper = $storesCountryResourceMapper;
        $this->store = $store;
    }

    /**
     * @param string $iso2Code
     *
     * @return \Generated\Shared\Transfer\StoreCountryRestAttributesTransfer
     */
    public function getStoresCountryAttributes(string $iso2Code): StoreCountryRestAttributesTransfer
    {
        $countryTransfer = (new CountryTransfer())->setIso2Code($iso2Code);
        $countryTransfer = $this->countryClient->getCountryByIso2Code($countryTransfer);
        $regions = $this->countryClient->getRegionsByCountryIso2Code($countryTransfer)->getRegions();

        return $this->storesCountryResourceMapper->mapCountryToStoresCountryRestAttributes($countryTransfer, $regions);
    }
}
