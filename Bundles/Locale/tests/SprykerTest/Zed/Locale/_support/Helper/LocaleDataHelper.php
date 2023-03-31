<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Locale\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\LocaleBuilder;
use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Locale\Persistence\SpyLocaleStoreQuery;
use Orm\Zed\Store\Persistence\SpyStoreQuery;
use Spryker\Zed\Locale\Business\LocaleFacadeInterface;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class LocaleDataHelper extends Module
{
    use LocatorHelperTrait;

    /**
     * @var int
     */
    public const LOCALE_NAME_LENGTH_LIMIT = 5;

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function haveLocale(array $seedData = []): LocaleTransfer
    {
        $localeTransfer = $this->generateLocaleTransfer($seedData);

        if ($this->getLocaleFacade()->hasLocale($localeTransfer->getLocaleName())) {
            return $this->getLocaleFacade()->getLocale($localeTransfer->getLocaleName());
        }

        return $this->getLocaleFacade()->createLocale($localeTransfer->getLocaleName());
    }

    /**
     * @param int $idStore
     * @param int $idLocale
     *
     * @return int
     */
    public function haveLocaleStore(int $idStore, int $idLocale): int
    {
        $localeStoreEntity = $this->createLocaleStorePropelQuery()
            ->filterByFkStore($idStore)
            ->filterByFkLocale($idLocale)
            ->findOneOrCreate();

        $localeStoreEntity->save();

        return $localeStoreEntity->getIdLocaleStore();
    }

    /**
     * @param int $idStore
     * @param int $idLocale
     *
     * @return bool
     */
    public function localeStoreExists(int $idStore, int $idLocale): bool
    {
        return $this->createLocaleStorePropelQuery()
            ->filterByFkStore($idStore)
            ->filterByFkLocale($idLocale)
            ->exists();
    }

    /**
     * @param int $idStore
     *
     * @return void
     */
    public function deleteLocaleStore(int $idStore): void
    {
        $this->createLocaleStorePropelQuery()
            ->filterByFkStore($idStore)
            ->delete();
    }

    /**
     * @param int $idStore
     *
     * @return int
     */
    public function getDefaultLocaleByIdStore(int $idStore): int
    {
        return $this->createStorePropelQuery()
            ->findPk($idStore)
            ->getFkLocale();
    }

    /**
     * @return void
     */
    public function ensureStoreLocaleDatabaseTableIsEmpty(): void
    {
        $localeStoreQuery = $this->createLocaleStorePropelQuery();
        $localeStoreQuery->deleteAll();
    }

    /**
     * @param int $idLocale
     *
     * @return int
     */
    public function countLocaleStoreRelations(int $idLocale): int
    {
        return $this->createLocaleStorePropelQuery()
            ->filterByFkLocale($idLocale)
            ->count();
    }

    /**
     * @param string $storeName
     *
     * @return int
     */
    public function getDefaultLocaleIdByStoreName(string $storeName): int
    {
        return $this->createStorePropelQuery()
            ->findOneByName($storeName)
            ->getFkLocale();
    }

    /**
     * @return \Orm\Zed\Store\Persistence\SpyStoreQuery
     */
    protected function createStorePropelQuery(): SpyStoreQuery
    {
        return SpyStoreQuery::create();
    }

    /**
     * @return \Orm\Zed\Locale\Persistence\SpyLocaleStoreQuery
     */
    protected function createLocaleStorePropelQuery(): SpyLocaleStoreQuery
    {
        return SpyLocaleStoreQuery::create();
    }

    /**
     * @return \Spryker\Zed\Locale\Business\LocaleFacadeInterface
     */
    protected function getLocaleFacade(): LocaleFacadeInterface
    {
        return $this->getLocator()->locale()->facade();
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer|\Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    protected function generateLocaleTransfer(array $seedData = [])
    {
        $localeTransfer = (new LocaleBuilder($seedData))->build();

        if (strlen($localeTransfer->getLocaleName()) > static::LOCALE_NAME_LENGTH_LIMIT) {
            return $this->generateLocaleTransfer($seedData);
        }

        return $localeTransfer;
    }
}
