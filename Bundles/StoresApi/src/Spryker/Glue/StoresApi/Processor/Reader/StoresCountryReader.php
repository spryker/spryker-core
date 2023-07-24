<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\StoresApi\Processor\Reader;

use Generated\Shared\Transfer\ApiStoreCountryAttributesTransfer;
use Generated\Shared\Transfer\CountryCollectionTransfer;
use Generated\Shared\Transfer\CountryTransfer;
use Spryker\Glue\StoresApi\Dependency\Client\StoresApiToCountryClientInterface;
use Spryker\Glue\StoresApi\Processor\Mapper\StoresCountryResourceMapperInterface;

class StoresCountryReader implements StoresCountryReaderInterface
{
    /**
     * @var \Spryker\Glue\StoresApi\Dependency\Client\StoresApiToCountryClientInterface
     */
    protected $countryClient;

    /**
     * @var \Spryker\Glue\StoresApi\Processor\Mapper\StoresCountryResourceMapperInterface
     */
    protected $storesCountryResourceMapper;

    /**
     * @param \Spryker\Glue\StoresApi\Dependency\Client\StoresApiToCountryClientInterface $countryClient
     * @param \Spryker\Glue\StoresApi\Processor\Mapper\StoresCountryResourceMapperInterface $storesCountryResourceMapper
     */
    public function __construct(
        StoresApiToCountryClientInterface $countryClient,
        StoresCountryResourceMapperInterface $storesCountryResourceMapper
    ) {
        $this->countryClient = $countryClient;
        $this->storesCountryResourceMapper = $storesCountryResourceMapper;
    }

    /**
     * @param array $iso2Codes
     *
     * @return array<\Generated\Shared\Transfer\ApiStoreCountryAttributesTransfer>
     */
    public function getStoresCountryAttributes(array $iso2Codes): array
    {
        $storeCountryAttributes = [];

        $countryCollectionTransfer = new CountryCollectionTransfer();
        foreach ($iso2Codes as $iso2Code) {
            $countryCollectionTransfer->addCountries((new CountryTransfer())->setIso2Code($iso2Code));
        }
        $countryCollectionTransfer = $this->countryClient->findCountriesByIso2Codes($countryCollectionTransfer);

        foreach ($countryCollectionTransfer->getCountries() as $countryTransfer) {
            $storeCountryAttributes[] = $this->storesCountryResourceMapper->mapCountryToStoresCountryRestAttributes(
                $countryTransfer,
                new ApiStoreCountryAttributesTransfer(),
            );
        }

        return $storeCountryAttributes;
    }
}
