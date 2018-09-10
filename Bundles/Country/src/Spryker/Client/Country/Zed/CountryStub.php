<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Country\Zed;

use Generated\Shared\Transfer\CountryCollectionTransfer;
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
     * @param \Generated\Shared\Transfer\CountryCollectionTransfer $countryCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\CountryCollectionTransfer
     */
    public function findCountriesByIso2Codes(CountryCollectionTransfer $countryCollectionTransfer): CountryCollectionTransfer
    {
        /** @var \Generated\Shared\Transfer\CountryCollectionTransfer $countryCollectionTransfer */
        $countryCollectionTransfer = $this->zedRequestClient->call('/country/gateway/find-countries-by-iso2-codes', $countryCollectionTransfer);

        return $countryCollectionTransfer;
    }
}
