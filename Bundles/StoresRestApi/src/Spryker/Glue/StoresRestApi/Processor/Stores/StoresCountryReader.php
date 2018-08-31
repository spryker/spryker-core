<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\StoresRestApi\Processor\Stores;

use Generated\Shared\Transfer\CountryRequestTransfer;
use Spryker\Glue\StoresRestApi\Dependency\Client\StoresRestApiToCountryClientInterface;
use Spryker\Glue\StoresRestApi\Processor\Mapper\StoresCountryResourceMapperInterface;

class StoresCountryReader implements StoresCountryReaderInterface
{
    /**
     * @var \Spryker\Glue\StoresRestApi\Dependency\Client\StoresRestApiToCountryClientInterface
     */
    protected $countryClient;

    /**
     * @var \Spryker\Glue\StoresRestApi\Processor\Mapper\StoresCountryResourceMapperInterface
     */
    protected $storesCountryResourceMapper;

    /**
     * @param \Spryker\Glue\StoresRestApi\Dependency\Client\StoresRestApiToCountryClientInterface $countryClient
     * @param \Spryker\Glue\StoresRestApi\Processor\Mapper\StoresCountryResourceMapperInterface $storesCountryResourceMapper
     */
    public function __construct(
        StoresRestApiToCountryClientInterface $countryClient,
        StoresCountryResourceMapperInterface $storesCountryResourceMapper
    ) {
        $this->countryClient = $countryClient;
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
        $countryCollectionTransfer = $this->countryClient->findCountriesByIso2Codes(
            (new CountryRequestTransfer())
                ->setIso2Codes($iso2Codes)
        );
        foreach ($countryCollectionTransfer->getCountries() as $countryTransfer) {
            $storeCountryAttributes[] = $this->storesCountryResourceMapper->mapCountryToStoresCountryRestAttributes(
                $countryTransfer
            );
        }

        return $storeCountryAttributes;
    }
}
