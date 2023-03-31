<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Country\Persistence;

use Generated\Shared\Transfer\CountryCollectionTransfer;
use Generated\Shared\Transfer\CountryTransfer;

interface CountryRepositoryInterface
{
    /**
     * @param array<string> $iso2Codes
     *
     * @return \Generated\Shared\Transfer\CountryCollectionTransfer
     */
    public function getCountriesByIso2Codes(array $iso2Codes): CountryCollectionTransfer;

    /**
     * Result format:
     * [
     *     $idStore => [
     *         'codes' => [$iso2Code, ...],
     *         'names' => [$countryName, ...]
     *     ],
     *     ...
     * ]
     *
     * @phpstan-return array<int, array<string, array<int, string>>>
     *
     * @param array<int> $storeIds
     *
     * @return array<int, array>
     */
    public function getCountryDataGroupedByIdStore(array $storeIds): array;

    /**
     * @param string $iso2Code
     *
     * @return int
     */
    public function countCountriesByIso2Code(string $iso2Code): int;

    /**
     * @param string $iso2Code
     *
     * @return int
     */
    public function getRegionsCountByIso2Code(string $iso2Code): int;

    /**
     * @return \Generated\Shared\Transfer\CountryCollectionTransfer
     */
    public function getCountryCollection(): CountryCollectionTransfer;

    /**
     * @param string $countryName
     *
     * @return \Generated\Shared\Transfer\CountryTransfer|null
     */
    public function findCountryByName(string $countryName): ?CountryTransfer;

    /**
     * @param string $iso2Code
     *
     * @return \Generated\Shared\Transfer\CountryTransfer|null
     */
    public function findCountryByIso2Code(string $iso2Code): ?CountryTransfer;

    /**
     * @param string $iso3Code
     *
     * @return \Generated\Shared\Transfer\CountryTransfer|null
     */
    public function findCountryByIso3Code(string $iso3Code): ?CountryTransfer;
}
