<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Country\Business\Expander;

use ArrayObject;
use Generated\Shared\Transfer\CountryCollectionTransfer;
use Spryker\Zed\Country\Persistence\CountryRepositoryInterface;

class RegionExpander implements RegionExpanderInterface
{
    /**
     * @var \Spryker\Zed\Country\Persistence\CountryRepositoryInterface
     */
    protected CountryRepositoryInterface $countryRepository;

    /**
     * @param \Spryker\Zed\Country\Persistence\CountryRepositoryInterface $countryRepository
     */
    public function __construct(CountryRepositoryInterface $countryRepository)
    {
        $this->countryRepository = $countryRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\CountryCollectionTransfer $countryCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\CountryCollectionTransfer
     */
    public function expandCountryCollectionWithRegions(CountryCollectionTransfer $countryCollectionTransfer): CountryCollectionTransfer
    {
        $countryIds = $this->extractCountryIdsFromCountryTransfers(
            $countryCollectionTransfer->getCountries(),
        );

        $regionTransfersGroupedByIdCountry = $this->countryRepository
            ->getRegionsGroupedByIdCountry($countryIds);

        $countryTransfers = $this->addRegionsToCountryTransfers(
            $countryCollectionTransfer->getCountries(),
            $regionTransfersGroupedByIdCountry,
        );

        return $countryCollectionTransfer->setCountries($countryTransfers);
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\CountryTransfer> $countryTransfers
     * @param array<int, list<\Generated\Shared\Transfer\RegionTransfer>> $regionTransfersGroupedByIdCountry
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\CountryTransfer>
     */
    protected function addRegionsToCountryTransfers(
        ArrayObject $countryTransfers,
        array $regionTransfersGroupedByIdCountry
    ): ArrayObject {
        foreach ($countryTransfers as $countryTransfer) {
            $regionTransfers = new ArrayObject(
                $regionTransfersGroupedByIdCountry[$countryTransfer->getIdCountryOrFail()] ?? [],
            );

            $countryTransfer->setRegions($regionTransfers);
        }

        return $countryTransfers;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\CountryTransfer> $countryTransfers
     *
     * @return list<int>
     */
    protected function extractCountryIdsFromCountryTransfers(ArrayObject $countryTransfers): array
    {
        $countryIds = [];

        foreach ($countryTransfers as $countryTransfer) {
            $countryIds[] = $countryTransfer->getIdCountryOrFail();
        }

        return $countryIds;
    }
}
