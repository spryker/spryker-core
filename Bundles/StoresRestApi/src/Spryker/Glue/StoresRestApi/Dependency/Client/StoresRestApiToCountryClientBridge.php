<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\StoresRestApi\Dependency\Client;

class StoresRestApiToCountryClientBridge implements StoresRestApiToCountryClientInterface
{
    /**
     * @var \Spryker\Client\Country\CountryClientInterface
     */
    protected $countryClient;

    /**
     * @param \Spryker\Client\Country\CountryClientInterface $countryClient
     */
    public function __construct($countryClient)
    {
        $this->countryClient = $countryClient;
    }

    /**
     * @param \Generated\Shared\Transfer\CountryRequestTransfer $countryRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CountryTransfer
     */
    public function getCountryByIso2Code($countryRequestTransfer)
    {
        return $this->countryClient->getCountryByIso2Code($countryRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\RegionRequestTransfer $regionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\RegionCollectionTransfer
     */
    public function getRegionsByCountryIso2Code($regionRequestTransfer)
    {
        return $this->countryClient->getRegionsByCountryIso2Code($regionRequestTransfer);
    }
}
