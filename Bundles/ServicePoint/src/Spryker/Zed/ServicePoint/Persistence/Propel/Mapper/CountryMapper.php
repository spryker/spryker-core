<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePoint\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CountryTransfer;
use Generated\Shared\Transfer\RegionTransfer;
use Orm\Zed\Country\Persistence\SpyCountry;
use Orm\Zed\Country\Persistence\SpyRegion;

class CountryMapper
{
    /**
     * @param \Orm\Zed\Country\Persistence\SpyCountry $countryEntity
     * @param \Generated\Shared\Transfer\CountryTransfer $countryTransfer
     *
     * @return \Generated\Shared\Transfer\CountryTransfer
     */
    public function mapCountryEntityToCountryTransfer(
        SpyCountry $countryEntity,
        CountryTransfer $countryTransfer
    ): CountryTransfer {
        return $countryTransfer->fromArray($countryEntity->toArray(), true);
    }

    /**
     * @param \Orm\Zed\Country\Persistence\SpyRegion $regionEntity
     * @param \Generated\Shared\Transfer\RegionTransfer $regionTransfer
     *
     * @return \Generated\Shared\Transfer\RegionTransfer
     */
    public function mapRegionEntityToRegionTransfer(
        SpyRegion $regionEntity,
        RegionTransfer $regionTransfer
    ): RegionTransfer {
        return $regionTransfer->fromArray($regionEntity->toArray(), true);
    }
}
