<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\StoresApi\Dependency\Client;

use Generated\Shared\Transfer\CountryCollectionTransfer;
use Spryker\Client\Country\CountryClientInterface;

class StoresApiToCountryClientBridge implements StoresApiToCountryClientInterface
{
    /**
     * @var \Spryker\Client\Country\CountryClientInterface
     */
    protected CountryClientInterface $countryClient;

    /**
     * @param \Spryker\Client\Country\CountryClientInterface $countryClient
     */
    public function __construct($countryClient)
    {
        $this->countryClient = $countryClient;
    }

    /**
     * @param \Generated\Shared\Transfer\CountryCollectionTransfer $countryCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\CountryCollectionTransfer
     */
    public function findCountriesByIso2Codes(CountryCollectionTransfer $countryCollectionTransfer): CountryCollectionTransfer
    {
        return $this->countryClient->findCountriesByIso2Codes($countryCollectionTransfer);
    }
}
