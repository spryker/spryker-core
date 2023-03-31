<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Country\Persistence;

use Generated\Shared\Transfer\CountryTransfer;
use Generated\Shared\Transfer\RegionTransfer;

interface CountryEntityManagerInterface
{
    /**
     * @param int $storeId
     * @param array<int> $countryIds
     *
     * @return void
     */
    public function updateStoresCountries(int $storeId, array $countryIds): void;

    /**
     * @param \Generated\Shared\Transfer\CountryTransfer $countryTransfer
     *
     * @return \Generated\Shared\Transfer\CountryTransfer
     */
    public function createCountry(CountryTransfer $countryTransfer): CountryTransfer;

    /**
     * @param \Generated\Shared\Transfer\RegionTransfer $regionTransfer
     *
     * @return \Generated\Shared\Transfer\RegionTransfer
     */
    public function createRegion(RegionTransfer $regionTransfer): RegionTransfer;
}
