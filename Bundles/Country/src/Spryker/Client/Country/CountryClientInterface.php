<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Country;

use Generated\Shared\Transfer\CountryTransfer;
use Generated\Shared\Transfer\RegionCollectionTransfer;

interface CountryClientInterface
{
    /**
     * Specification:
     * - Retrieves country by CountryTransfer::getIso2Code.
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
     * - Retrieves regions by CountryTransfer::getIso2Code.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CountryTransfer $countryTransfer
     *
     * @return \Generated\Shared\Transfer\RegionCollectionTransfer
     */
    public function getRegionsByCountryIso2Code(CountryTransfer $countryTransfer): RegionCollectionTransfer;
}
