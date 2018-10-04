<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Country\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CountryCollectionTransfer;
use Generated\Shared\Transfer\CountryTransfer;
use Generated\Shared\Transfer\RegionTransfer;
use Generated\Shared\Transfer\SpyCountryEntityTransfer;
use Generated\Shared\Transfer\SpyRegionEntityTransfer;

class CountryMapper implements CountryMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\SpyRegionEntityTransfer $regionEntityTransfer
     *
     * @return \Generated\Shared\Transfer\RegionTransfer
     */
    public function mapRegionTransfer(SpyRegionEntityTransfer $regionEntityTransfer): RegionTransfer
    {
        $regionTransfer = (new RegionTransfer())
            ->fromArray($regionEntityTransfer->toArray(), true);

        return $regionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyCountryEntityTransfer[] $countryEntityTransfers
     *
     * @return \Generated\Shared\Transfer\CountryCollectionTransfer
     */
    public function mapCountryTransferCollection(array $countryEntityTransfers): CountryCollectionTransfer
    {
        $countryCollectionTransfer = new CountryCollectionTransfer();

        foreach ($countryEntityTransfers as $countryEntityTransfer) {
            $countryCollectionTransfer->addCountries(
                $this->mapCountryTransfer(
                    $countryEntityTransfer
                )
            );
        }

        return $countryCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyCountryEntityTransfer $countryEntityTransfer
     *
     * @return \Generated\Shared\Transfer\CountryTransfer
     */
    public function mapCountryTransfer(SpyCountryEntityTransfer $countryEntityTransfer): CountryTransfer
    {
        $countryTransfer = (new CountryTransfer())
            ->fromArray($countryEntityTransfer->toArray(), true);

        foreach ($countryEntityTransfer->getSpyRegions() as $regionEntityTransfer) {
            $countryTransfer->addRegion(
                $this->mapRegionTransfer($regionEntityTransfer)
            );
        }

        return $countryTransfer;
    }
}
