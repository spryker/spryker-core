<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Country\Persistence;

use Generated\Shared\Transfer\CountryCollectionTransfer;
use Generated\Shared\Transfer\CountryCriteriaTransfer;
use Generated\Shared\Transfer\CountryTransfer;
use Orm\Zed\Country\Persistence\Map\SpyCountryStoreTableMap;
use Orm\Zed\Country\Persistence\Map\SpyCountryTableMap;
use Orm\Zed\Country\Persistence\SpyCountryQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\Country\Persistence\CountryPersistenceFactory getFactory()
 */
class CountryRepository extends AbstractRepository implements CountryRepositoryInterface
{
    /**
     * @var string
     */
    protected const KEY_CODES = 'codes';

    /**
     * @var string
     */
    protected const KEY_NAMES = 'names';

    /**
     * @param array<string> $iso2Codes
     *
     * @return \Generated\Shared\Transfer\CountryCollectionTransfer
     */
    public function getCountriesByIso2Codes(array $iso2Codes): CountryCollectionTransfer
    {
        $countryEntities = $this->getFactory()
            ->createCountryQuery()
            ->joinWithSpyRegion(Criteria::LEFT_JOIN)
            ->filterByIso2Code_In($iso2Codes)
            ->find();

        return $this->getFactory()
            ->createCountryMapper()
            ->mapCountryTransferCollection($countryEntities, new CountryCollectionTransfer());
    }

    /**
     * Result format:
     * [
     *     $idStore => [
     *         'codes' => ['DE', 'AT', ...],
     *         'names' => ['Germany', 'Austria', ...]
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
    public function getCountryDataGroupedByIdStore(array $storeIds): array
    {
        $countryQuery = $this->getFactory()
            ->createCountryQuery();
        $countryQuery->useCountryStoreQuery()
                ->filterByFkStore_In($storeIds)
                ->orderByIdCountryStore()
            ->endUse();
        $countryQuery->select([SpyCountryTableMap::COL_ISO2_CODE, SpyCountryTableMap::COL_NAME, SpyCountryStoreTableMap::COL_FK_STORE]);
        /** @var \Propel\Runtime\Collection\ObjectCollection $countries */
        $countries = $countryQuery->find();

        $countryCodesByStoreId = [];
        foreach ($countries->toArray() as $countryData) {
            /** @var int $fkStore */
            $fkStore = $countryData[SpyCountryStoreTableMap::COL_FK_STORE];
            /** @var string $iso2code */
            $iso2code = $countryData[SpyCountryTableMap::COL_ISO2_CODE];
            /** @var string $name */
            $name = $countryData[SpyCountryTableMap::COL_NAME];

            $countryCodesByStoreId[$fkStore][static::KEY_CODES][] = $iso2code;
            $countryCodesByStoreId[$fkStore][static::KEY_NAMES][] = $name;
        }

        return $countryCodesByStoreId;
    }

    /**
     * @param string $iso2Code
     *
     * @return int
     */
    public function countCountriesByIso2Code(string $iso2Code): int
    {
        return $this->getFactory()
            ->createCountryQuery()
            ->filterByIso2Code($iso2Code)
            ->count();
    }

    /**
     * @param string $iso2Code
     *
     * @return int
     */
    public function getRegionsCountByIso2Code(string $iso2Code): int
    {
        return $this->getFactory()
            ->createRegionQuery()
            ->filterByIso2Code($iso2Code)
            ->count();
    }

    /**
     * @return \Generated\Shared\Transfer\CountryCollectionTransfer
     */
    public function getAvailableCountries(): CountryCollectionTransfer
    {
        $countryEntities = $this->getFactory()
            ->createCountryQuery()
            ->orderByName()
            ->find();

        return $this->getFactory()
            ->createCountryMapper()
            ->mapCountryTransferCollection($countryEntities, new CountryCollectionTransfer());
    }

    /**
     * @param string $countryName
     *
     * @return \Generated\Shared\Transfer\CountryTransfer|null
     */
    public function findCountryByName(string $countryName): ?CountryTransfer
    {
        $countryEntity = $this->getFactory()
            ->createCountryQuery()
            ->filterByName($countryName)
            ->findOne();

        if ($countryEntity === null) {
            return null;
        }

        return $this->getFactory()
            ->createCountryMapper()
            ->mapCountryTransfer($countryEntity, new CountryTransfer());
    }

    /**
     * @param string $iso2Code
     *
     * @return \Generated\Shared\Transfer\CountryTransfer|null
     */
    public function findCountryByIso2Code(string $iso2Code): ?CountryTransfer
    {
        $countryEntity = $this
            ->getFactory()
            ->createCountryQuery()
            ->filterByIso2Code($iso2Code)
            ->findOne();

        if ($countryEntity === null) {
            return null;
        }

        return $this->getFactory()
            ->createCountryMapper()
            ->mapCountryTransfer($countryEntity, new CountryTransfer());
    }

    /**
     * @param string $iso3Code
     *
     * @return \Generated\Shared\Transfer\CountryTransfer|null
     */
    public function findCountryByIso3Code(string $iso3Code): ?CountryTransfer
    {
        $countryEntity = $this
            ->getFactory()
            ->createCountryQuery()
            ->filterByIso3Code($iso3Code)
            ->findOne();

        if ($countryEntity === null) {
            return null;
        }

        return $this->getFactory()
            ->createCountryMapper()
            ->mapCountryTransfer($countryEntity, new CountryTransfer());
    }

    /**
     * @param \Generated\Shared\Transfer\CountryCriteriaTransfer $countryCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\CountryCollectionTransfer
     */
    public function getCountryCollection(
        CountryCriteriaTransfer $countryCriteriaTransfer
    ): CountryCollectionTransfer {
        $countryQuery = $this->getFactory()->createCountryQuery();

        $countryQuery = $this->applyCountryFilters($countryQuery, $countryCriteriaTransfer);

        return $this->getFactory()
            ->createCountryMapper()
            ->mapCountryEntitiesToCountryCollectionTransfer(
                $countryQuery->find(),
                new CountryCollectionTransfer(),
            );
    }

    /**
     * @param list<int> $countryIds
     *
     * @return array<int, list<\Generated\Shared\Transfer\RegionTransfer>>
     */
    public function getRegionsGroupedByIdCountry(array $countryIds): array
    {
        $regionEntities = $this->getFactory()
            ->createRegionQuery()
            ->filterByFkCountry_In($countryIds)
            ->find();

        return $this->getFactory()
            ->createCountryMapper()
            ->mapRegionEntitiesToRegionTransfersGroupedByIdCountry($regionEntities);
    }

    /**
     * @param \Orm\Zed\Country\Persistence\SpyCountryQuery $countryQuery
     * @param \Generated\Shared\Transfer\CountryCriteriaTransfer $countryCriteriaTransfer
     *
     * @return \Orm\Zed\Country\Persistence\SpyCountryQuery
     */
    protected function applyCountryFilters(
        SpyCountryQuery $countryQuery,
        CountryCriteriaTransfer $countryCriteriaTransfer
    ): SpyCountryQuery {
        $countryConditionsTransfer = $countryCriteriaTransfer->getCountryConditions();

        if (!$countryConditionsTransfer) {
            return $countryQuery;
        }

        if ($countryConditionsTransfer->getIso2Codes()) {
            $countryQuery->filterByIso2Code_In($countryConditionsTransfer->getIso2Codes());
        }

        return $countryQuery;
    }
}
