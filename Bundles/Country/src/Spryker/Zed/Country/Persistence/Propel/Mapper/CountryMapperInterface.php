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

interface CountryMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\SpyRegionEntityTransfer $regionEntityTransfer
     *
     * @return \Generated\Shared\Transfer\RegionTransfer
     */
    public function mapRegionTransfer(SpyRegionEntityTransfer $regionEntityTransfer): RegionTransfer;

    /**
     * @param \Generated\Shared\Transfer\SpyCountryEntityTransfer[] $countryEntityTransfers
     *
     * @return \Generated\Shared\Transfer\CountryCollectionTransfer
     */
    public function mapCountryTransferCollection(array $countryEntityTransfers): CountryCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\SpyCountryEntityTransfer $countryEntityTransfer
     *
     * @return \Generated\Shared\Transfer\CountryTransfer
     */
    public function mapCountryTransfer(SpyCountryEntityTransfer $countryEntityTransfer): CountryTransfer;
}
