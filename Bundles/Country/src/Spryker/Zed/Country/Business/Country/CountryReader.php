<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Country\Business\Country;

use Generated\Shared\Transfer\CountryCollectionTransfer;
use Generated\Shared\Transfer\CountryTransfer;
use Spryker\Zed\Country\Business\Exception\MissingCountryException;
use Spryker\Zed\Country\Persistence\CountryRepositoryInterface;

class CountryReader implements CountryReaderInterface
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
     * @param \Generated\Shared\Transfer\CountryCollectionTransfer $countryCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\CountryCollectionTransfer
     */
    public function getCountriesByIso2CodesFromCountryCollection(CountryCollectionTransfer $countryCollectionTransfer): CountryCollectionTransfer
    {
        $iso2Codes = [];
        foreach ($countryCollectionTransfer->getCountries() as $countryTransfer) {
            $iso2Codes[] = $countryTransfer->getIso2CodeOrFail();
        }

        return $this->countryRepository->getCountriesByIso2Codes($iso2Codes);
    }

    /**
     * @param array<string> $iso2Codes
     *
     * @return \Generated\Shared\Transfer\CountryCollectionTransfer
     */
    public function getCountriesByIso2Codes(array $iso2Codes): CountryCollectionTransfer
    {
        return $this->countryRepository->getCountriesByIso2Codes($iso2Codes);
    }

    /**
     * @param string $iso2code
     *
     * @throws \Spryker\Zed\Country\Business\Exception\MissingCountryException
     *
     * @return \Generated\Shared\Transfer\CountryTransfer
     */
    public function getCountryByIso2Code(string $iso2code): CountryTransfer
    {
        $countryTransfer = $this->countryRepository->findCountryByIso2Code($iso2code);

        if ($countryTransfer === null) {
            throw new MissingCountryException(sprintf('Country not found for country ISO 2 code: %s', $iso2code));
        }

        return $countryTransfer;
    }

    /**
     * @param string $iso3code
     *
     * @throws \Spryker\Zed\Country\Business\Exception\MissingCountryException
     *
     * @return \Generated\Shared\Transfer\CountryTransfer
     */
    public function getCountryByIso3Code(string $iso3code): CountryTransfer
    {
        $countryTransfer = $this->countryRepository->findCountryByIso3Code($iso3code);

        if ($countryTransfer === null) {
            throw new MissingCountryException(sprintf('Country not found for country ISO 3 code: %s', $iso3code));
        }

        return $countryTransfer;
    }

    /**
     * @param string $iso2code
     *
     * @return bool
     */
    public function countryExists(string $iso2code): bool
    {
        return $this->countryRepository->countCountriesByIso2Code($iso2code) > 0;
    }

    /**
     * @return \Generated\Shared\Transfer\CountryCollectionTransfer
     */
    public function getCountryCollection(): CountryCollectionTransfer
    {
        return $this->countryRepository->getCountryCollection();
    }

    /**
     * @param string $countryName
     *
     * @return \Generated\Shared\Transfer\CountryTransfer
     */
    public function getPreferredCountryByName(string $countryName): CountryTransfer
    {
        $countryTransfer = $this->countryRepository->findCountryByName($countryName);

        return $countryTransfer ?? new CountryTransfer();
    }
}
