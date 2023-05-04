<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Country\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\CountryBuilder;
use Generated\Shared\Transfer\CountryTransfer;
use Orm\Zed\Country\Persistence\SpyCountryQuery;
use Orm\Zed\Country\Persistence\SpyCountryStoreQuery;
use Spryker\Zed\Country\Business\CountryFacadeInterface;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class CountryDataHelper extends Module
{
    use LocatorHelperTrait;
    use DataCleanupHelperTrait;

    /**
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\CountryTransfer
     */
    public function haveCountry(array $seed = []): CountryTransfer
    {
        $countryTransferBuilder = new CountryBuilder($seed);
        $countryTransfer = $countryTransferBuilder->build();

        return $this->getCountryFacade()->getCountryByIso2Code(
            $countryTransfer->getIso2Code(),
        );
    }

    /**
     * @param array<string, mixed> $seed
     *
     * @return \Generated\Shared\Transfer\CountryTransfer
     */
    public function haveCountryTransfer(array $seed = []): CountryTransfer
    {
        $countryTransfer = (new CountryBuilder($seed))->build();

        $countryEntity = SpyCountryQuery::create()
            ->filterByIso2Code($countryTransfer->getIso2Code())
            ->findOneOrCreate();
        $countryEntity->fromArray($countryTransfer->modifiedToArray());
        $countryEntity->save();

        $this->getDataCleanupHelper()->_addCleanup(function () use ($countryEntity): void {
            $this->deleteCountry($countryEntity->getIdCountry());
        });

        return $countryTransfer->fromArray($countryEntity->toArray());
    }

    /**
     * @param int $idStore
     * @param int $idCountry
     *
     * @return int
     */
    public function haveCountryStore(int $idStore, int $idCountry): int
    {
        $countryStoreEntity = $this->createCountryStorePropelQuery()
            ->filterByFkStore($idStore)
            ->filterByFkCountry($idCountry)
            ->findOneOrCreate();

        $countryStoreEntity->save();

        return $countryStoreEntity->getIdCountryStore();
    }

    /**
     * @param int $idStore
     * @param int $idCountry
     *
     * @return bool
     */
    public function countryStoreExists(int $idStore, int $idCountry): bool
    {
        return $this->createCountryStorePropelQuery()
            ->filterByFkStore($idStore)
            ->filterByFkCountry($idCountry)
            ->exists();
    }

    /**
     * @param int $idStore
     *
     * @return void
     */
    public function deleteCountryStore(int $idStore): void
    {
        $this->createCountryStorePropelQuery()
            ->filterByFkStore($idStore)
            ->delete();
    }

    /**
     * @return \Orm\Zed\Country\Persistence\SpyCountryStoreQuery
     */
    protected function createCountryStorePropelQuery(): SpyCountryStoreQuery
    {
        return SpyCountryStoreQuery::create();
    }

    /**
     * @param int $idCountry
     *
     * @return void
     */
    protected function deleteCountry(int $idCountry): void
    {
        $countryEntity = $this->getCountryQuery()->findOneByIdCountry($idCountry);

        if ($countryEntity) {
            $countryEntity->delete();
        }
    }

    /**
     * @return \Orm\Zed\Country\Persistence\SpyCountryQuery
     */
    protected function getCountryQuery(): SpyCountryQuery
    {
        return SpyCountryQuery::create();
    }

    /**
     * @return \Spryker\Zed\Country\Business\CountryFacadeInterface
     */
    protected function getCountryFacade(): CountryFacadeInterface
    {
        return $this->getLocator()->country()->facade();
    }

    /**
     * @return void
     */
    public function ensureCountryStoreDatabaseTableIsEmpty(): void
    {
        $countryStoreQuery = $this->createCountryStorePropelQuery();
        $countryStoreQuery->deleteAll();
    }

    /**
     * @return int
     */
    public function getCountryStoreRelationsCount(): int
    {
        return $this->createCountryStorePropelQuery()->count();
    }
}
