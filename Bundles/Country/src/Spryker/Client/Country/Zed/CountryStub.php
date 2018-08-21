<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Country\Zed;

use Generated\Shared\Transfer\CountryRequestTransfer;
use Generated\Shared\Transfer\CountryTransfer;
use Generated\Shared\Transfer\RegionCollectionTransfer;
use Generated\Shared\Transfer\RegionRequestTransfer;
use Spryker\Client\Country\Dependency\Client\CountryToZedRequestClientInterface;

class CountryStub implements CountryStubInterface
{
    /**
     * @var \Spryker\Client\Country\Dependency\Client\CountryToZedRequestClientInterface
     */
    protected $zedRequestClient;

    /**
     * @param \Spryker\Client\Country\Dependency\Client\CountryToZedRequestClientInterface $zedRequestClient
     */
    public function __construct(CountryToZedRequestClientInterface $zedRequestClient)
    {
        $this->zedRequestClient = $zedRequestClient;
    }

    /**
     * @param \Generated\Shared\Transfer\CountryRequestTransfer $countryRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CountryTransfer
     */
    public function getCountryByIso2Code(CountryRequestTransfer $countryRequestTransfer): CountryTransfer
    {
        /** @var \Generated\Shared\Transfer\CountryTransfer $countryTransfer */
        $countryTransfer = $this->zedRequestClient->call('/country/gateway/get-country-by-iso2-code', $countryRequestTransfer);

        return $countryTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\RegionRequestTransfer $regionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\RegionCollectionTransfer
     */
    public function getRegionsByCountryIso2Code(RegionRequestTransfer $regionRequestTransfer): RegionCollectionTransfer
    {
        /** @var \Generated\Shared\Transfer\RegionCollectionTransfer $regionCollectionTransfer */
        $regionCollectionTransfer = $this->zedRequestClient->call('/country/gateway/get-regions-by-country-iso2-code', $regionRequestTransfer);

        return $regionCollectionTransfer;
    }
}
