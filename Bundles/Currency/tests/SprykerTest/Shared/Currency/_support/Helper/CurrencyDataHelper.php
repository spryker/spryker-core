<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Currency\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\CurrencyBuilder;
use Generated\Shared\Transfer\CurrencyTransfer;
use Orm\Zed\Currency\Persistence\SpyCurrencyQuery;
use Orm\Zed\Currency\Persistence\SpyCurrencyStoreQuery;
use Spryker\Zed\Currency\Business\CurrencyFacadeInterface;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class CurrencyDataHelper extends Module
{
    use DataCleanupHelperTrait;
    use LocatorHelperTrait;

    /**
     * @param array $override
     *
     * @return int
     */
    public function haveCurrency(array $override = []): int
    {
        $currencyTransfer = (new CurrencyBuilder($override))->build();

        $idCurrency = $this->getCurrencyFacade()->createCurrency($currencyTransfer);

        $this->getDataCleanupHelper()->_addCleanup(function () use ($idCurrency): void {
            $spyCurrencyEntity = SpyCurrencyQuery::create()->findOneByIdCurrency($idCurrency);
            if ($spyCurrencyEntity) {
                $spyCurrencyEntity->delete();
            }
        });

        return $idCurrency;
    }

    /**
     * @param array $override
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    public function haveCurrencyTransfer(array $override = []): CurrencyTransfer
    {
        $idCurrency = $this->haveCurrency($override);

        return $this->getCurrencyFacade()->getByIdCurrency($idCurrency);
    }

    /**
     * @param int $idStore
     * @param int $idCurrency
     *
     * @return int
     */
    public function haveCurrencyStore(int $idStore, int $idCurrency): int
    {
        $currencyStoreEntity = $this->createCurrencyStorePropelQuery()
            ->filterByFkStore($idStore)
            ->filterByFkCurrency($idCurrency)
            ->findOneOrCreate();

        $currencyStoreEntity->save();

        $this->getDataCleanupHelper()->_addCleanup(function () use ($currencyStoreEntity): void {
            $spyCurrencyStoreEntity = $this->createCurrencyStorePropelQuery()->findOneByIdCurrencyStore($currencyStoreEntity->getIdCurrencyStore());
            if ($spyCurrencyStoreEntity) {
                $spyCurrencyStoreEntity->delete();
            }
        });

        return $currencyStoreEntity->getIdCurrencyStore();
    }

    /**
     * @param int $idStore
     * @param int $idCurrency
     *
     * @return bool
     */
    public function currencyStoreExists(int $idStore, int $idCurrency): bool
    {
        return $this->createCurrencyStorePropelQuery()
            ->filterByFkStore($idStore)
            ->filterByFkCurrency($idCurrency)
            ->exists();
    }

    /**
     * @param int $idStore
     *
     * @return void
     */
    public function deleteCurrencyStore(int $idStore): void
    {
        $currencyStoreQuery = $this->createCurrencyStorePropelQuery();

        $currencyStoreQuery
            ->filterByFkStore($idStore)
            ->delete();
    }

    /**
     * @return void
     */
    public function ensureCurrencyStoreDatabaseTableIsEmpty(): void
    {
        $countryStoreQuery = $this->createCurrencyStorePropelQuery();
        $countryStoreQuery->deleteAll();
    }

    /**
     * @return int
     */
    public function getCurrencyStoreRelationsCount(): int
    {
        return $this->createCurrencyStorePropelQuery()->count();
    }

    /**
     * @return \Orm\Zed\Currency\Persistence\SpyCurrencyStoreQuery
     */
    protected function createCurrencyStorePropelQuery(): SpyCurrencyStoreQuery
    {
        return SpyCurrencyStoreQuery::create();
    }

    /**
     * @return \Spryker\Zed\Currency\Business\CurrencyFacadeInterface
     */
    protected function getCurrencyFacade(): CurrencyFacadeInterface
    {
        return $this->getLocator()->currency()->facade();
    }
}
