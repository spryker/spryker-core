<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\MerchantCommission\Helper;

use ArrayObject;
use Codeception\Module;
use Generated\Shared\DataBuilder\MerchantCommissionAmountBuilder;
use Generated\Shared\DataBuilder\MerchantCommissionBuilder;
use Generated\Shared\DataBuilder\MerchantCommissionGroupBuilder;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MerchantCommissionAmountTransfer;
use Generated\Shared\Transfer\MerchantCommissionGroupTransfer;
use Generated\Shared\Transfer\MerchantCommissionTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommissionAmountQuery;
use Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommissionGroupQuery;
use Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommissionMerchantQuery;
use Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommissionQuery;
use Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommissionStoreQuery;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;

class MerchantCommissionHelper extends Module
{
    use DataCleanupHelperTrait;

    /**
     * @see \Spryker\Zed\MerchantCommission\Communication\Plugin\MerchantCommission\FixedMerchantCommissionCalculatorPlugin::CALCULATOR_TYPE
     *
     * @var string
     */
    protected const FIXED_CALCULATOR_TYPE = 'fixed';

    /**
     * @see \Spryker\Zed\MerchantCommission\Communication\Plugin\MerchantCommission\PercentageMerchantCommissionCalculatorPlugin::CALCULATOR_TYPE
     *
     * @var string
     */
    protected const PERCENTAGE_CALCULATOR_TYPE = 'percentage';

    /**
     * @param array<string, mixed> $seedData
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionGroupTransfer
     */
    public function haveMerchantCommissionGroup(array $seedData = []): MerchantCommissionGroupTransfer
    {
        $merchantCommissionGroupTransfer = (new MerchantCommissionGroupBuilder($seedData))->build();

        $merchantCommissionGroupEntity = $this->getMerchantCommissionGroupQuery()
            ->filterByKey($merchantCommissionGroupTransfer->getKeyOrFail())
            ->findOneOrCreate();
        $merchantCommissionGroupEntity->fromArray($merchantCommissionGroupTransfer->modifiedToArray());
        $merchantCommissionGroupEntity->save();

        $this->getDataCleanupHelper()->_addCleanup(function () use ($merchantCommissionGroupEntity): void {
            $merchantCommissionGroupEntity->delete();
        });

        return $merchantCommissionGroupTransfer->fromArray($merchantCommissionGroupEntity->toArray(), true);
    }

    /**
     * @return void
     */
    public function sanitizeAllMerchantCommissions(): void
    {
        $this->getMerchantCommissionAmountQuery()->deleteAll();
        $this->getMerchantCommissionMerchantQuery()->deleteAll();
        $this->getMerchantCommissionStoreQuery()->deleteAll();
        $this->getMerchantCommissionQuery()->deleteAll();
        $this->getMerchantCommissionGroupQuery()->deleteAll();
    }

    /**
     * @param array<string, mixed> $seedData
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionTransfer
     */
    public function haveMerchantCommission(array $seedData = []): MerchantCommissionTransfer
    {
        $merchantCommissionTransfer = (new MerchantCommissionBuilder($seedData))->build();
        $merchantCommissionEntity = $this->getMerchantCommissionQuery()
            ->filterByKey($merchantCommissionTransfer->getKeyOrFail())
            ->findOneOrCreate();
        $merchantCommissionEntity->fromArray($merchantCommissionTransfer->modifiedToArray());
        $merchantCommissionEntity->setFkMerchantCommissionGroup(
            $merchantCommissionTransfer->getMerchantCommissionGroupOrFail()->getIdMerchantCommissionGroupOrFail(),
        );
        $merchantCommissionEntity->save();

        $merchantCommissionAmountTransfers = [];
        foreach ($merchantCommissionTransfer->getMerchantCommissionAmounts() as $merchantCommissionAmountTransfer) {
            $merchantCommissionAmountTransfer->setFkMerchantCommission($merchantCommissionEntity->getIdMerchantCommission());
            $merchantCommissionAmountTransfers[] = $this->haveMerchantCommissionAmount($merchantCommissionAmountTransfer->modifiedToArray());
        }
        $merchantCommissionTransfer->setMerchantCommissionAmounts(new ArrayObject($merchantCommissionAmountTransfers));

        if ($merchantCommissionTransfer->getStoreRelation()) {
            $this->haveMerchantCommissionStoreRelations(
                $merchantCommissionTransfer->getStoreRelationOrFail(),
                $merchantCommissionEntity->getIdMerchantCommission(),
            );
        }

        if ($merchantCommissionTransfer->getMerchants()->count() !== 0) {
            $this->haveMerchantCommissionMerchantRelations(
                $merchantCommissionTransfer->getMerchants(),
                $merchantCommissionEntity->getIdMerchantCommission(),
            );
        }

        $this->getDataCleanupHelper()->_addCleanup(function () use ($merchantCommissionEntity): void {
            $merchantCommissionEntity->delete();
        });

        return $merchantCommissionTransfer->fromArray($merchantCommissionEntity->toArray(), true);
    }

    /**
     * @param list<\Generated\Shared\Transfer\MerchantTransfer> $merchantTransfers
     * @param \Generated\Shared\Transfer\MerchantCommissionGroupTransfer $merchantCommissionGroupTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param int $percentageAmount
     * @param list<string> $categoryKeys
     * @param int $priceFrom
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionTransfer
     */
    public function havePercentageCommissionByCategoryAndPriceFromCondition(
        array $merchantTransfers,
        MerchantCommissionGroupTransfer $merchantCommissionGroupTransfer,
        StoreTransfer $storeTransfer,
        int $percentageAmount,
        array $categoryKeys,
        int $priceFrom
    ): MerchantCommissionTransfer {
        $itemCondition = sprintf("item-price >= '%s' AND category IS IN '%s'", $priceFrom, implode(';', $categoryKeys));

        $plainMerchants = [];
        foreach ($merchantTransfers as $merchantTransfer) {
            $plainMerchants[] = $merchantTransfer->toArray();
        }

        return $this->haveMerchantCommission([
            MerchantCommissionTransfer::MERCHANT_COMMISSION_GROUP => $merchantCommissionGroupTransfer,
            MerchantCommissionTransfer::AMOUNT => $percentageAmount,
            MerchantCommissionTransfer::CALCULATOR_TYPE_PLUGIN => static::PERCENTAGE_CALCULATOR_TYPE,
            MerchantCommissionTransfer::ITEM_CONDITION => $itemCondition,
            MerchantCommissionTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
            MerchantCommissionTransfer::MERCHANTS => $plainMerchants,
        ]);
    }

    /**
     * @param list<\Generated\Shared\Transfer\MerchantTransfer> $merchantTransfers
     * @param \Generated\Shared\Transfer\MerchantCommissionGroupTransfer $merchantCommissionGroupTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param int $fixedAmount
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     * @param list<string> $categoryKeys
     * @param int $priceFrom
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionTransfer
     */
    public function haveFixedCommissionByCategoryAndPriceFromCondition(
        array $merchantTransfers,
        MerchantCommissionGroupTransfer $merchantCommissionGroupTransfer,
        StoreTransfer $storeTransfer,
        int $fixedAmount,
        CurrencyTransfer $currencyTransfer,
        array $categoryKeys,
        int $priceFrom
    ): MerchantCommissionTransfer {
        $itemCondition = sprintf("item-price >= '%s' AND category IS IN '%s'", $priceFrom, implode(';', $categoryKeys));

        $plainMerchants = [];
        foreach ($merchantTransfers as $merchantTransfer) {
            $plainMerchants[] = $merchantTransfer->toArray();
        }

        $merchantCommissionAmountTransfer = (new MerchantCommissionAmountBuilder([
            MerchantCommissionAmountTransfer::GROSS_AMOUNT => $fixedAmount,
            MerchantCommissionAmountTransfer::NET_AMOUNT => $fixedAmount,
            MerchantCommissionAmountTransfer::CURRENCY => $currencyTransfer,
        ]))->build();

        return $this->haveMerchantCommission([
            MerchantCommissionTransfer::MERCHANT_COMMISSION_GROUP => $merchantCommissionGroupTransfer,
            MerchantCommissionTransfer::AMOUNT => 0,
            MerchantCommissionTransfer::CALCULATOR_TYPE_PLUGIN => static::FIXED_CALCULATOR_TYPE,
            MerchantCommissionTransfer::ITEM_CONDITION => $itemCondition,
            MerchantCommissionTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
            MerchantCommissionTransfer::MERCHANTS => $plainMerchants,
            MerchantCommissionTransfer::MERCHANT_COMMISSION_AMOUNTS => [$merchantCommissionAmountTransfer->toArray()],
        ]);
    }

    /**
     * @param array<string, mixed> $seedData
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionAmountTransfer
     */
    public function haveMerchantCommissionAmount(array $seedData = []): MerchantCommissionAmountTransfer
    {
        $merchantCommissionAmountTransfer = (new MerchantCommissionAmountBuilder($seedData))->build();

        $merchantCommissionAmountEntity = $this->getMerchantCommissionAmountQuery()
            ->filterByFkMerchantCommission($merchantCommissionAmountTransfer->getFkMerchantCommissionOrFail())
            ->filterByFkCurrency($merchantCommissionAmountTransfer->getCurrencyOrFail()->getIdCurrencyOrFail())
            ->findOneOrCreate();
        $merchantCommissionAmountEntity->fromArray($merchantCommissionAmountTransfer->toArray());
        $merchantCommissionAmountEntity->save();

        $this->getDataCleanupHelper()->_addCleanup(function () use ($merchantCommissionAmountEntity): void {
            $merchantCommissionAmountEntity->delete();
        });

        return $merchantCommissionAmountTransfer->fromArray($merchantCommissionAmountEntity->toArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $storeRelationTransfer
     * @param int $idMerchantCommission
     *
     * @return void
     */
    protected function haveMerchantCommissionStoreRelations(
        StoreRelationTransfer $storeRelationTransfer,
        int $idMerchantCommission
    ): void {
        foreach ($storeRelationTransfer->getStores() as $storeTransfer) {
            $merchantCommissionStoreEntity = $this->getMerchantCommissionStoreQuery()
                ->filterByFkMerchantCommission($idMerchantCommission)
                ->filterByFkStore($storeTransfer->getIdStoreOrFail())
                ->findOneOrCreate();
            $merchantCommissionStoreEntity->save();
        }
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantTransfer> $merchantTransfers
     * @param int $idMerchantCommission
     *
     * @return void
     */
    protected function haveMerchantCommissionMerchantRelations(
        ArrayObject $merchantTransfers,
        int $idMerchantCommission
    ): void {
        foreach ($merchantTransfers as $merchantTransfer) {
            $merchantCommissionMerchantEntity = $this->getMerchantCommissionMerchantQuery()
                ->filterByFkMerchantCommission($idMerchantCommission)
                ->filterByFkMerchant($merchantTransfer->getIdMerchantOrFail())
                ->findOneOrCreate();
            $merchantCommissionMerchantEntity->save();
        }
    }

    /**
     * @return \Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommissionGroupQuery
     */
    protected function getMerchantCommissionGroupQuery(): SpyMerchantCommissionGroupQuery
    {
        return SpyMerchantCommissionGroupQuery::create();
    }

    /**
     * @return \Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommissionQuery
     */
    protected function getMerchantCommissionQuery(): SpyMerchantCommissionQuery
    {
        return SpyMerchantCommissionQuery::create();
    }

    /**
     * @return \Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommissionAmountQuery
     */
    protected function getMerchantCommissionAmountQuery(): SpyMerchantCommissionAmountQuery
    {
        return SpyMerchantCommissionAmountQuery::create();
    }

    /**
     * @return \Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommissionStoreQuery
     */
    protected function getMerchantCommissionStoreQuery(): SpyMerchantCommissionStoreQuery
    {
        return SpyMerchantCommissionStoreQuery::create();
    }

    /**
     * @return \Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommissionMerchantQuery
     */
    protected function getMerchantCommissionMerchantQuery(): SpyMerchantCommissionMerchantQuery
    {
        return SpyMerchantCommissionMerchantQuery::create();
    }
}
