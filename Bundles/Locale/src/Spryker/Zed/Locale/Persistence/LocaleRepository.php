<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Locale\Persistence;

use Generated\Shared\Transfer\LocaleCriteriaTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Locale\Persistence\Map\SpyLocaleStoreTableMap;
use Orm\Zed\Locale\Persistence\Map\SpyLocaleTableMap;
use Orm\Zed\Store\Persistence\Map\SpyStoreTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Collection\Collection;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\Locale\Persistence\LocalePersistenceFactory getFactory()
 */
class LocaleRepository extends AbstractRepository implements LocaleRepositoryInterface
{
    /**
     * @param array<string> $localeNames
     *
     * @return array<\Generated\Shared\Transfer\LocaleTransfer>
     */
    public function getLocaleTransfersByLocaleNames(array $localeNames): array
    {
        $localeEntities = $this->getFactory()->createLocalePropelQuery()
            ->filterByLocaleName_In($localeNames)
            ->find();

        return $this->mapLocaleEntitiesToLocaleTransfers($localeEntities);
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleCriteriaTransfer|null $localeCriteriaTransfer
     *
     * @return array<\Generated\Shared\Transfer\LocaleTransfer>
     */
    public function getLocaleCollectionByCriteria(?LocaleCriteriaTransfer $localeCriteriaTransfer = null): array
    {
        $localeQuery = $this->getFactory()->createLocalePropelQuery();

        $localeConditionTransfer = ($localeCriteriaTransfer !== null) ? $localeCriteriaTransfer->getLocaleConditions() : null;
        if ($localeConditionTransfer && $localeConditionTransfer->getLocaleNames()) {
            $localeQuery->filterByLocaleName_In($localeConditionTransfer->getLocaleNames());
        }

        if ($localeConditionTransfer && $localeConditionTransfer->getAssignedToStore()) {
            $localeQuery->useLocaleStoreQuery()
                ->filterByFkStore(null, Criteria::ISNOTNULL)
                ->endUse();
        }

        if ($localeConditionTransfer && $localeConditionTransfer->getStoreNames()) {
            $localeQuery->useLocaleStoreQuery()
                ->useStoreQuery()
                ->filterByName_In($localeConditionTransfer->getStoreNames())
                ->endUse()
                ->endUse();
        }

        if ($localeConditionTransfer && $localeConditionTransfer->getIsActive()) {
            $localeQuery->filterByIsActive(true);
        }

        $localeEntites = $localeQuery->find();

        return $this->mapLocaleEntitiesToLocaleTransfers($localeEntites);
    }

    /**
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer|null
     */
    public function findLocaleTransferByLocaleName(string $localeName): ?LocaleTransfer
    {
        $localeEntity = $this->getFactory()->createLocalePropelQuery()
            ->filterByLocaleName($localeName)
            ->findOne();

        if (!$localeEntity) {
            return null;
        }

        return $this->getFactory()
            ->createLocaleMapper()
            ->mapLocaleEntityToLocaleTransfer($localeEntity, new LocaleTransfer());
    }

    /**
     * @param string $localeName
     *
     * @return int
     */
    public function getLocalesCountByLocaleName(string $localeName): int
    {
        return $this->getFactory()
            ->createLocalePropelQuery()
            ->filterByLocaleName($localeName)
            ->count();
    }

    /**
     * @param mixed $localeEntity
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer|null
     */
    protected function getMappedLocaleTransfer($localeEntity, LocaleTransfer $localeTransfer): ?LocaleTransfer
    {
        if (!$localeEntity) {
            return null;
        }

        return $this->getFactory()
            ->createLocaleMapper()
            ->mapLocaleEntityToLocaleTransfer($localeEntity, $localeTransfer);
    }

    /**
     * @param int $idLocale
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer|null
     */
    public function findLocaleByIdLocale(int $idLocale): ?LocaleTransfer
    {
        $localeEntity = $this->getFactory()->createLocalePropelQuery()
            ->filterByIdLocale($idLocale)
            ->findOne();

        return $this->getMappedLocaleTransfer($localeEntity, new LocaleTransfer());
    }

    /**
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer|null
     */
    public function findLocaleByLocaleName(string $localeName): ?LocaleTransfer
    {
        $localeEntity = $this->getFactory()->createLocalePropelQuery()
            ->filterByLocaleName($localeName)
            ->findOne();

        return $this->getMappedLocaleTransfer($localeEntity, new LocaleTransfer());
    }

    /**
     * Result format:
     * [
     *     $idStore => ['en_US', 'de_DE', ...],
     *     ...
     * ]
     *
     * @phpstan-return array<int, array<int, string>>
     *
     * @param array<int> $storeIds
     *
     * @return array<array<string>>
     */
    public function getLocaleNamesGroupedByIdStore(array $storeIds): array
    {
        $localeQuery = $this->getFactory()
            ->createLocalePropelQuery();
        $localeQuery->useLocaleStoreQuery()
            ->filterByFkStore_In($storeIds)
            ->endUse();
        $localeQuery->select([SpyLocaleTableMap::COL_LOCALE_NAME, SpyLocaleStoreTableMap::COL_FK_STORE]);
        /** @var \Propel\Runtime\Collection\ObjectCollection $locales */
        $locales = $localeQuery->find();

        $localeCodesByStoreId = [];
        foreach ($locales->toArray() as $localeData) {
            /** @var int $fkStore */
            $fkStore = $localeData[SpyLocaleStoreTableMap::COL_FK_STORE];
            /** @var string $name */
            $name = $localeData[SpyLocaleTableMap::COL_LOCALE_NAME];

            $localeCodesByStoreId[$fkStore][] = $name;
        }

        return $localeCodesByStoreId;
    }

    /**
     * Result format:
     * [
     *     $idStore => $localeName,
     *     ...
     * ]
     *
     * @param array<int> $storeIds
     *
     * @return array<string>
     */
    public function getDefaultLocaleNamesIndexedByIdStore(array $storeIds): array
    {
        $localeQuery = $this->getFactory()
            ->getStorePropelQuery();
        $localeQuery->filterByIdStore_In($storeIds)
            ->useDefaultLocaleQuery()
            ->endUse();
        $localeQuery->select([SpyLocaleTableMap::COL_LOCALE_NAME, SpyStoreTableMap::COL_ID_STORE]);
        /** @var \Propel\Runtime\Collection\ObjectCollection $locales */
        $locales = $localeQuery->find();

        $localeNamesIndexedByIdStore = [];

        foreach ($locales->toArray() as $localeData) {
            /** @var int $idStore */
            $idStore = $localeData[SpyStoreTableMap::COL_ID_STORE];
            /** @var string $name */
            $name = $localeData[SpyLocaleTableMap::COL_LOCALE_NAME];

            $localeNamesIndexedByIdStore[$idStore] = $name;
        }

        return $localeNamesIndexedByIdStore;
    }

    /**
     * @param \Propel\Runtime\Collection\Collection<\Orm\Zed\Locale\Persistence\SpyLocale> $localeEntities
     *
     * @return array<\Generated\Shared\Transfer\LocaleTransfer>
     */
    protected function mapLocaleEntitiesToLocaleTransfers(Collection $localeEntities): array
    {
        $localeTransfers = [];
        $localeMapper = $this->getFactory()->createLocaleMapper();

        foreach ($localeEntities as $localeEntity) {
            $localeTransfer = new LocaleTransfer();
            $localeTransfer = $localeMapper->mapLocaleEntityToLocaleTransfer($localeEntity, $localeTransfer);

            $localeTransfers[] = $localeTransfer;
        }

        return $localeTransfers;
    }
}
