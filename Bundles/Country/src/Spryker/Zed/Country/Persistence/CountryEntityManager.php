<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Country\Persistence;

use Generated\Shared\Transfer\CountryTransfer;
use Generated\Shared\Transfer\RegionTransfer;
use Orm\Zed\Country\Persistence\SpyCountry;
use Orm\Zed\Country\Persistence\SpyRegion;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\Country\Persistence\CountryPersistenceFactory getFactory()
 */
class CountryEntityManager extends AbstractEntityManager implements CountryEntityManagerInterface
{
    use TransactionTrait;

    /**
     * @param int $storeId
     * @param array<int> $countryIds
     *
     * @return void
     */
    public function updateStoresCountries(int $storeId, array $countryIds): void
    {
        $this->getTransactionHandler()->handleTransaction(function () use ($storeId, $countryIds) {
            $this->executeUpdateStoresCountries($storeId, $countryIds);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\CountryTransfer $countryTransfer
     *
     * @return \Generated\Shared\Transfer\CountryTransfer
     */
    public function createCountry(CountryTransfer $countryTransfer): CountryTransfer
    {
        $countryMapper = $this->getFactory()->createCountryMapper();
        $countryEntity = $countryMapper
            ->mapCountryTransferToCountryEntity($countryTransfer, new SpyCountry());

        $countryEntity->save();

        return $countryMapper->mapCountryEntityToCountryTransfer($countryEntity, $countryTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\RegionTransfer $regionTransfer
     *
     * @return \Generated\Shared\Transfer\RegionTransfer
     */
    public function createRegion(RegionTransfer $regionTransfer): RegionTransfer
    {
        $countryMapper = $this->getFactory()->createCountryMapper();
        $regionEntity = $countryMapper
            ->mapRegionTransferToRegionEntity($regionTransfer, new SpyRegion());

        $regionEntity->save();

        return $countryMapper->mapRegionEntityToRegionTransfer($regionEntity, $regionTransfer);
    }

    /**
     * @param int $storeId
     * @param array<int> $countryIds
     *
     * @return void
     */
    protected function executeUpdateStoresCountries(int $storeId, array $countryIds): void
    {
        foreach ($countryIds as $idCountry) {
            $channelPriceModeEntity = $this->getFactory()
                ->createCountryStorePropelQuery()
                ->filterByFkStore($storeId)
                ->filterByFkCountry($idCountry)
                ->findOneOrCreate();

            if ($channelPriceModeEntity->isNew()) {
                $channelPriceModeEntity->save();
            }
        }

        $this->getFactory()
            ->createCountryStorePropelQuery()
            ->filterByFkStore($storeId)
            ->filterByFkCountry($countryIds, Criteria::NOT_IN)
            ->find()
            ->delete();
    }
}
