<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\StoresRestApi\Processor\Stores;

use Generated\Shared\Transfer\CountryRequestTransfer;
use Generated\Shared\Transfer\RegionRequestTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\StoresRestApi\Dependency\Client\StoresRestApiToCountryClientInterface;
use Spryker\Glue\StoresRestApi\Processor\Mapper\StoresCountryResourceMapperInterface;

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
     * @param \Spryker\Glue\StoresRestApi\Dependency\Client\StoresRestApiToCountryClientInterface $countryClient
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\StoresRestApi\Processor\Mapper\StoresCountryResourceMapperInterface $storesCountryResourceMapper
     */
    public function __construct(
        StoresRestApiToCountryClientInterface $countryClient,
        RestResourceBuilderInterface $restResourceBuilder,
        StoresCountryResourceMapperInterface $storesCountryResourceMapper
    ) {
        $this->countryClient = $countryClient;
        $this->restResourceBuilder = $restResourceBuilder;
        $this->storesCountryResourceMapper = $storesCountryResourceMapper;
    }

    /**
     * @param array $iso2Codes
     *
     * @return \Generated\Shared\Transfer\StoreCountryRestAttributesTransfer[]
     */
    public function getStoresCountryAttributes(array $iso2Codes): array
    {
        $storeCountryAttributes = [];

        foreach ($iso2Codes as $iso2Code) {
            $countryRequestTransfer = (new CountryRequestTransfer())->setIso2Code($iso2Code);
            $regionRequestTransfer = (new RegionRequestTransfer())->setCountryIso2Code($iso2Code);
            $countryTransfer = $this->countryClient->getCountryByIso2Code($countryRequestTransfer);
            $regions = $this->countryClient->getRegionsByCountryIso2Code($regionRequestTransfer);

            $storeCountryAttributes[] = $this->storesCountryResourceMapper->mapCountryToStoresCountryRestAttributes(
                $countryTransfer,
                $regions
            );
        }

        return $storeCountryAttributes;
    }
}
