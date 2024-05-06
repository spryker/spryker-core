<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Locale\Persistence;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

/**
 * @method \Spryker\Zed\Locale\Persistence\LocalePersistenceFactory getFactory()
 */
class LocaleEntityManager extends AbstractEntityManager implements LocaleEntityManagerInterface
{
    use TransactionTrait;

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return void
     */
    public function createLocaleStore(StoreTransfer $storeTransfer, LocaleTransfer $localeTransfer): void
    {
        $this->getFactory()->createLocaleStoreEntity()
            ->setFkStore($storeTransfer->getIdStoreOrFail())
            ->setFkLocale($localeTransfer->getIdLocaleOrFail())
            ->save();
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param array<\Generated\Shared\Transfer\LocaleTransfer> $localeTransfers
     *
     * @return void
     */
    public function updateStoreLocales(StoreTransfer $storeTransfer, array $localeTransfers): void
    {
        $this->getTransactionHandler()->handleTransaction(function () use ($storeTransfer, $localeTransfers) {
            $this->executeUpdateStoreLocales($storeTransfer, $localeTransfers);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return void
     */
    public function updateStoreDefaultLocale(StoreTransfer $storeTransfer, LocaleTransfer $localeTransfer): void
    {
        $locale = $this->getFactory()
            ->getStorePropelQuery()
            ->filterByIdStore($storeTransfer->getIdStoreOrFail())
            ->findOne();

        if ($locale !== null) {
            $locale->setFkLocale($localeTransfer->getIdLocaleOrFail())->save();
        }
    }

    /**
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function createLocale(string $localeName): LocaleTransfer
    {
        $localeEntity = $this->getFactory()->createLocaleEntity()
            ->setLocaleName($localeName);

        $localeEntity->save();

        return $this->getFactory()
            ->createLocaleMapper()
            ->mapLocaleEntityToLocaleTransfer($localeEntity, new LocaleTransfer());
    }

    /**
     * @param string $localeName
     *
     * @return void
     */
    public function deleteLocale(string $localeName): void
    {
        $locale = $this->getFactory()
            ->createLocalePropelQuery()
            ->filterByLocaleName($localeName)
            ->findOne();

        if ($locale !== null) {
            $locale->setIsActive(false)->save();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param array<\Generated\Shared\Transfer\LocaleTransfer> $localeTransfers
     *
     * @return void
     */
    protected function executeUpdateStoreLocales(StoreTransfer $storeTransfer, array $localeTransfers): void
    {
        $idStore = $storeTransfer->getIdStoreOrFail();
        $localeIds = [];
        foreach ($localeTransfers as $localeTransfer) {
            $localeStoreEntity = $this->getFactory()
                ->createLocaleStorePropelQuery()
                ->filterByFkStore($idStore)
                ->filterByFkLocale($localeTransfer->getIdLocaleOrFail())
                ->findOneOrCreate();

            if ($localeStoreEntity->isNew()) {
                $localeStoreEntity->save();
            }

            $localeIds[] = $localeTransfer->getIdLocaleOrFail();
        }

        /** @var \Propel\Runtime\Collection\ObjectCollection $localeStoreCollection */
        $localeStoreCollection = $this->getFactory()
            ->createLocaleStorePropelQuery()
            ->filterByFkStore($idStore)
            ->filterByFkLocale($localeIds, Criteria::NOT_IN)
            ->find();
        $localeStoreCollection->delete();
    }
}
