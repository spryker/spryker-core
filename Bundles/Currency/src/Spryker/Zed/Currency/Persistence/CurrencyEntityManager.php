<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Currency\Persistence;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Currency\Persistence\SpyCurrency;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Currency\Persistence\Exception\EntityNotFoundException;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\Currency\Persistence\CurrencyPersistenceFactory getFactory()
 */
class CurrencyEntityManager extends AbstractEntityManager implements CurrencyEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param array<\Generated\Shared\Transfer\CurrencyTransfer> $currencyTransfers
     *
     * @return void
     */
    public function updateCurrencyStores(StoreTransfer $storeTransfer, array $currencyTransfers): void
    {
        $idStore = $storeTransfer->getIdStoreOrFail();
        $currencyIds = [];
        foreach ($currencyTransfers as $currencyTransfer) {
            $currencyStoreEntity = $this->getFactory()
                ->createCurrencyStorePropelQuery()
                ->filterByFkStore($idStore)
                ->filterByFkCurrency($currencyTransfer->getIdCurrencyOrFail())
                ->findOneOrCreate();

            if ($currencyStoreEntity->isNew()) {
                $currencyStoreEntity->save();
            }

            $currencyIds[] = $currencyTransfer->getIdCurrencyOrFail();
        }

        /** @var \Propel\Runtime\Collection\ObjectCollection $currencyStoreCollection */
        $currencyStoreCollection = $this->getFactory()
            ->createCurrencyStorePropelQuery()
            ->filterByFkStore($idStore)
            ->filterByFkCurrency($currencyIds, Criteria::NOT_IN)
            ->find();
        $currencyStoreCollection->delete();
    }

    /**
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    public function createCurrency(CurrencyTransfer $currencyTransfer): CurrencyTransfer
    {
        $currencyEntity = $this->getFactory()
            ->createCurrencyMapper()
            ->mapCurrencyTransferToCurrencyEntity($currencyTransfer, new SpyCurrency());

        $currencyEntity->save();

        return $currencyTransfer->setIdCurrency($currencyEntity->getIdCurrency());
    }

    /**
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @throws \Spryker\Zed\Currency\Persistence\Exception\EntityNotFoundException
     *
     * @return void
     */
    public function updateStoreDefaultCurrency(CurrencyTransfer $currencyTransfer, StoreTransfer $storeTransfer): void
    {
        $storeEntity = $this->getFactory()
            ->getStorePropelQuery()
            ->filterByIdStore($storeTransfer->getIdStoreOrFail())
            ->findOne();

        if (!$storeEntity) {
            throw new EntityNotFoundException(sprintf('Store not found: %s', $storeTransfer->getIdStoreOrFail()));
        }

        $storeEntity
            ->setFkCurrency($currencyTransfer->getIdCurrencyOrFail())
            ->save();
    }
}
