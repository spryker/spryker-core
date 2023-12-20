<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddress\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CountryTransfer;
use Generated\Shared\Transfer\SpyCountryEntityTransfer;

class CountryMapper
{
    /**
     * @param \Generated\Shared\Transfer\SpyCountryEntityTransfer $countryEntityTransfer
     * @param \Generated\Shared\Transfer\CountryTransfer $countryTransfer
     *
     * @return \Generated\Shared\Transfer\CountryTransfer
     */
    public function mapCountryEntityTransferToCountryTransfer(
        SpyCountryEntityTransfer $countryEntityTransfer,
        CountryTransfer $countryTransfer
    ): CountryTransfer {
        return $countryTransfer->fromArray($countryEntityTransfer->toArray(), true);
    }
}
