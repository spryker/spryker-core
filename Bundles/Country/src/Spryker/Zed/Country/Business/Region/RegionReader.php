<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Country\Business\Region;

use Generated\Shared\Transfer\RegionCollectionTransfer;
use Spryker\Zed\Country\Persistence\CountryRepositoryInterface;

class RegionReader implements RegionReaderInterface
{
    /**
     * @var \Spryker\Zed\Country\Persistence\CountryRepositoryInterface
     */
    protected $countryRepository;

    /**
     * @var \Spryker\Zed\Country\Business\Region\RegionMapperInterface
     */
    protected $regionMapper;

    /**
     * @param \Spryker\Zed\Country\Persistence\CountryRepositoryInterface $countryRepository
     * @param \Spryker\Zed\Country\Business\Region\RegionMapperInterface $regionMapper
     */
    public function __construct(
        CountryRepositoryInterface $countryRepository,
        RegionMapperInterface $regionMapper
    ) {
        $this->countryRepository = $countryRepository;
        $this->regionMapper = $regionMapper;
    }

    /**
     * @param string $iso2Code
     *
     * @return \Generated\Shared\Transfer\RegionCollectionTransfer
     */
    public function getRegionsByCountryIso2Code(string $iso2Code): RegionCollectionTransfer
    {
        $regionCollection = $this->regionMapper->mapRegionsToRegionCollection(
            $this->countryRepository->getRegionsByCountryIso2Code($iso2Code)
        );

        return $regionCollection;
    }
}
