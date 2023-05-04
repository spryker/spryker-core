<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Country\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CountryCollectionTransfer;
use Generated\Shared\Transfer\CountryTransfer;
use Generated\Shared\Transfer\RegionTransfer;
use Orm\Zed\Country\Persistence\SpyCountry;
use Orm\Zed\Country\Persistence\SpyRegion;
use Propel\Runtime\Collection\ObjectCollection;

class CountryMapper
{
    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\Country\Persistence\SpyRegion> $regionEntities
     *
     * @return array<int, list<\Generated\Shared\Transfer\RegionTransfer>>
     */
    public function mapRegionEntitiesToRegionTransfersGroupedByIdCountry(ObjectCollection $regionEntities): array
    {
        $regionTransfersGroupedByIdCountry = [];

        foreach ($regionEntities as $regionEntity) {
            $regionTransfersGroupedByIdCountry[(int)$regionEntity->getFkCountry()][] = $this->mapRegionEntityToRegionTransfer(
                $regionEntity,
                new RegionTransfer(),
            );
        }

        return $regionTransfersGroupedByIdCountry;
    }

    /**
     * @param \Orm\Zed\Country\Persistence\SpyRegion $regionEntity
     * @param \Generated\Shared\Transfer\RegionTransfer $regionTransfer
     *
     * @return \Generated\Shared\Transfer\RegionTransfer
     */
    public function mapRegionEntityToRegionTransfer(SpyRegion $regionEntity, RegionTransfer $regionTransfer): RegionTransfer
    {
        return $regionTransfer->fromArray($regionEntity->toArray(), true);
    }

    /**
     * @param iterable<\Orm\Zed\Country\Persistence\SpyCountry> $countryEntities
     * @param \Generated\Shared\Transfer\CountryCollectionTransfer $countryCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\CountryCollectionTransfer
     */
    public function mapCountryTransferCollection(iterable $countryEntities, CountryCollectionTransfer $countryCollectionTransfer): CountryCollectionTransfer
    {
        foreach ($countryEntities as $countryEntity) {
            $countryCollectionTransfer->addCountries(
                $this->mapCountryTransfer(
                    $countryEntity,
                    new CountryTransfer(),
                ),
            );
        }

        return $countryCollectionTransfer;
    }

    /**
     * @param \Orm\Zed\Country\Persistence\SpyCountry $countryEntity
     * @param \Generated\Shared\Transfer\CountryTransfer $countryTransfer
     *
     * @return \Generated\Shared\Transfer\CountryTransfer
     */
    public function mapCountryTransfer(SpyCountry $countryEntity, CountryTransfer $countryTransfer): CountryTransfer
    {
        $countryTransfer = $countryTransfer
            ->fromArray($countryEntity->toArray(), true);

        foreach ($countryEntity->getSpyRegions() as $regionEntity) {
            $countryTransfer->addRegion(
                $this->mapRegionEntityToRegionTransfer($regionEntity, new RegionTransfer()),
            );
        }

        return $countryTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CountryTransfer $countryTransfer
     * @param \Orm\Zed\Country\Persistence\SpyCountry $countryEntity
     *
     * @return \Orm\Zed\Country\Persistence\SpyCountry
     */
    public function mapCountryTransferToCountryEntity(CountryTransfer $countryTransfer, SpyCountry $countryEntity): SpyCountry
    {
        return $countryEntity->setName($countryTransfer->getNameOrFail())
            ->setPostalCodeMandatory($countryTransfer->getPostalCodeMandatory())
            ->setPostalCodeRegex($countryTransfer->getPostalCodeRegex())
            ->setIso2Code($countryTransfer->getIso2CodeOrFail())
            ->setIso3Code($countryTransfer->getIso3CodeOrFail());
    }

    /**
     * @param \Orm\Zed\Country\Persistence\SpyCountry $countryEntity
     * @param \Generated\Shared\Transfer\CountryTransfer $countryTransfer
     *
     * @return \Generated\Shared\Transfer\CountryTransfer
     */
    public function mapCountryEntityToCountryTransfer(SpyCountry $countryEntity, CountryTransfer $countryTransfer): CountryTransfer
    {
        return $countryTransfer->fromArray($countryEntity->toArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\RegionTransfer $regionTransfer
     * @param \Orm\Zed\Country\Persistence\SpyRegion $regionEntity
     *
     * @return \Orm\Zed\Country\Persistence\SpyRegion
     */
    public function mapRegionTransferToRegionEntity(RegionTransfer $regionTransfer, SpyRegion $regionEntity): SpyRegion
    {
        return $regionEntity
            ->setIso2Code($regionTransfer->getIso2CodeOrFail())
            ->setFkCountry($regionTransfer->getFkCountryOrFail())
            ->setName($regionTransfer->getNameOrFail());
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\Country\Persistence\SpyCountry> $countryEntities
     * @param \Generated\Shared\Transfer\CountryCollectionTransfer $countryCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\CountryCollectionTransfer
     */
    public function mapCountryEntitiesToCountryCollectionTransfer(
        ObjectCollection $countryEntities,
        CountryCollectionTransfer $countryCollectionTransfer
    ): CountryCollectionTransfer {
        foreach ($countryEntities as $countryEntity) {
            $countryCollectionTransfer->addCountries(
                $this->mapCountryEntityToCountryTransfer($countryEntity, new CountryTransfer()),
            );
        }

        return $countryCollectionTransfer;
    }
}
