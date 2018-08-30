<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Country;

use Generated\Shared\Transfer\CountryCollectionTransfer;
use Generated\Shared\Transfer\CountryRequestTransfer;
use Generated\Shared\Transfer\CountryTransfer;

interface CountryClientInterface
{
    /**
     * Specification:
     * - Retrieves country by ISO-2 code.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CountryTransfer $countryTransfer
     *
     * @return \Generated\Shared\Transfer\CountryTransfer
     */
    public function getCountryByIso2Code(CountryTransfer $countryTransfer): CountryTransfer;

    /**
     * Specification:
     * - Retrieves countries by country ISO-2 code.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CountryRequestTransfer $countryRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CountryCollectionTransfer
     */
    public function findCountriesByIso2Codes(CountryRequestTransfer $countryRequestTransfer): CountryCollectionTransfer;
}
