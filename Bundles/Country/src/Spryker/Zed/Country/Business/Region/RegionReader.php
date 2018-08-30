<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Country\Business\Region;

use Generated\Shared\Transfer\CountryCollectionTransfer;
use Generated\Shared\Transfer\CountryRequestTransfer;
use Spryker\Zed\Country\Persistence\CountryRepositoryInterface;

class RegionReader implements RegionReaderInterface
{
    /**
     * @var \Spryker\Zed\Country\Persistence\CountryRepositoryInterface
     */
    protected $countryRepository;

    /**
     * @param \Spryker\Zed\Country\Persistence\CountryRepositoryInterface $countryRepository
     */
    public function __construct(
        CountryRepositoryInterface $countryRepository
    ) {
        $this->countryRepository = $countryRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\CountryRequestTransfer $countryRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CountryCollectionTransfer
     */
    public function findCountriesByIso2Codes(CountryRequestTransfer $countryRequestTransfer): CountryCollectionTransfer
    {
        return $this->countryRepository->findCountriesByIso2Codes($countryRequestTransfer);
    }
}
