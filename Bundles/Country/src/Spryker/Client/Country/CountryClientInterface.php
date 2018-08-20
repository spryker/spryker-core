<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Country;

use Generated\Shared\Transfer\CountryRequestTransfer;
use Generated\Shared\Transfer\CountryTransfer;
use Generated\Shared\Transfer\RegionCollectionTransfer;
use Generated\Shared\Transfer\RegionRequestTransfer;

interface CountryClientInterface
{
    /**
     * Specification:
     * - Retrieves country by ISO-2 code.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CountryRequestTransfer $countryRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CountryTransfer
     */
    public function getCountryByIso2Code(CountryRequestTransfer $countryRequestTransfer): CountryTransfer;

    /**
     * Specification:
     * - Retrieves regions by country ISO-2 code.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RegionRequestTransfer $regionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\RegionCollectionTransfer
     */
    public function getRegionsByCountryIso2Code(RegionRequestTransfer $regionRequestTransfer): RegionCollectionTransfer;
}
