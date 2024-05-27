<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantCommission;

use ArrayObject;
use Codeception\Actor;
use Generated\Shared\DataBuilder\MerchantCommissionBuilder;
use Generated\Shared\Transfer\MerchantCommissionAmountTransfer;
use Generated\Shared\Transfer\MerchantCommissionGroupTransfer;
use Generated\Shared\Transfer\MerchantCommissionTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\MerchantCommission\Persistence\Base\SpyMerchantCommissionMerchantQuery;
use Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommission;
use Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommissionAmount;
use Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommissionAmountQuery;
use Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommissionQuery;
use Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommissionStoreQuery;

/**
 * Inherited Methods
 *
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 * @method \Spryker\Zed\MerchantCommission\Business\MerchantCommissionFacadeInterface getFacade(?string $moduleName = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
class MerchantCommissionBusinessTester extends Actor
{
    use _generated\MerchantCommissionBusinessTesterActions;

    /**
     * @param array<string, mixed> $seedData
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionTransfer
     */
    public function createMerchantCommission(array $seedData = []): MerchantCommissionTransfer
    {
        if (!isset($seedData[MerchantCommissionTransfer::MERCHANT_COMMISSION_GROUP])) {
            $merchantCommissionGroupTransfer = $this->haveMerchantCommissionGroup();
            $seedData[MerchantCommissionTransfer::MERCHANT_COMMISSION_GROUP] = $merchantCommissionGroupTransfer;
        }

        if (!isset($seedData[MerchantCommissionTransfer::STORE_RELATION])) {
            $storeTransfer = $this->haveStore();
            $seedData[MerchantCommissionTransfer::STORE_RELATION] = (new StoreRelationTransfer())->addStores($storeTransfer);
        }

        if (!isset($seedData[MerchantCommissionTransfer::MERCHANTS])) {
            $merchantTransfer = $this->haveMerchant();
            $seedData[MerchantCommissionTransfer::MERCHANTS] = [$merchantTransfer->toArray()];
        }

        return $this->haveMerchantCommission($seedData);
    }

    /**
     * @return \Generated\Shared\Transfer\MerchantCommissionTransfer
     */
    public function createMerchantCommissionTransfer(): MerchantCommissionTransfer
    {
        $storeTransfer = $this->haveStore();
        $merchantCommissionGroupTransfer = $this->haveMerchantCommissionGroup();

        return (new MerchantCommissionBuilder([
            MerchantCommissionTransfer::MERCHANT_COMMISSION_GROUP => [
                MerchantCommissionGroupTransfer::UUID => $merchantCommissionGroupTransfer->getUuidOrFail(),
            ],
            MerchantCommissionTransfer::STORE_RELATION => [
                StoreRelationTransfer::STORES => [
                    [StoreTransfer::NAME => $storeTransfer->getNameOrFail()],
                ],
            ],
        ]))->build();
    }

    /**
     * @param array<string, mixed> $seedData
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionTransfer
     */
    public function createMerchantCommissionForImport(array $seedData = []): MerchantCommissionTransfer
    {
        if (!isset($seedData[MerchantCommissionTransfer::MERCHANT_COMMISSION_GROUP])) {
            $merchantCommissionGroupTransfer = $this->haveMerchantCommissionGroup();
            $seedData[MerchantCommissionTransfer::MERCHANT_COMMISSION_GROUP] = $merchantCommissionGroupTransfer;
        }

        if (!isset($seedData[MerchantCommissionTransfer::STORE_RELATION])) {
            $storeTransfer = $this->haveStore();
            $seedData[MerchantCommissionTransfer::STORE_RELATION] = (new StoreRelationTransfer())->addStores($storeTransfer);
        }

        if (!isset($seedData[MerchantCommissionTransfer::MERCHANTS])) {
            $merchantTransfer = $this->haveMerchant();
            $seedData[MerchantCommissionTransfer::MERCHANTS] = [$merchantTransfer->toArray()];
        }

        $merchantCommissionData = $this->haveMerchantCommission($seedData)->toArray();
        unset($merchantCommissionData[MerchantCommissionTransfer::ID_MERCHANT_COMMISSION]);
        unset($merchantCommissionData[MerchantCommissionTransfer::UUID]);

        return (new MerchantCommissionTransfer())->fromArray($merchantCommissionData, true);
    }

    /**
     * @return \Generated\Shared\Transfer\MerchantCommissionTransfer
     */
    public function createMerchantCommissionTransferForImport(): MerchantCommissionTransfer
    {
        $storeTransfer = $this->haveStore();
        $merchantCommissionGroupTransfer = $this->haveMerchantCommissionGroup();

        return (new MerchantCommissionBuilder([
            MerchantCommissionTransfer::MERCHANT_COMMISSION_GROUP => [
                MerchantCommissionGroupTransfer::KEY => $merchantCommissionGroupTransfer->getKeyOrFail(),
            ],
            MerchantCommissionTransfer::STORE_RELATION => [
                StoreRelationTransfer::STORES => [
                    [StoreTransfer::NAME => $storeTransfer->getNameOrFail()],
                ],
            ],
        ]))->build();
    }

    /**
     * @param int $idMerchantCommission
     *
     * @return \Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommission
     */
    public function getMerchantCommissionEntity(int $idMerchantCommission): SpyMerchantCommission
    {
        return $this->getMerchantCommissionQuery()
            ->findOneByIdMerchantCommission($idMerchantCommission);
    }

    /**
     * @param int $idMerchantCommission
     * @param int $idCurrency
     *
     * @return \Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommissionAmount|null
     */
    public function findMerchantCommissionAmountEntity(int $idMerchantCommission, int $idCurrency): ?SpyMerchantCommissionAmount
    {
        return $this->getMerchantCommissionAmountQuery()
            ->filterByFkMerchantCommission($idMerchantCommission)
            ->filterByFkCurrency($idCurrency)
            ->findOne();
    }

    /**
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $storeRelationTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $expectedStoreTransfer
     *
     * @return bool
     */
    public function storeRelationTransferHasStore(StoreRelationTransfer $storeRelationTransfer, StoreTransfer $expectedStoreTransfer): bool
    {
        foreach ($storeRelationTransfer->getStores() as $storeTransfer) {
            if ($storeTransfer->getIdStoreOrFail() === $expectedStoreTransfer->getIdStoreOrFail()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantTransfer> $merchantTransfers
     * @param \Generated\Shared\Transfer\MerchantTransfer $expectedMerchantTransfer
     *
     * @return bool
     */
    public function merchantCollectionHasMerchant(ArrayObject $merchantTransfers, MerchantTransfer $expectedMerchantTransfer): bool
    {
        foreach ($merchantTransfers as $merchantTransfer) {
            if ($merchantTransfer->getIdMerchantOrFail() === $expectedMerchantTransfer->getIdMerchantOrFail()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantCommissionAmountTransfer> $merchantCommissionAmountTransfers
     * @param \Generated\Shared\Transfer\MerchantCommissionAmountTransfer $expectedMerchantCommissionAmountTransfer
     *
     * @return bool
     */
    public function merchantCommissionAmountCollectionHasMerchantCommissionAmount(
        ArrayObject $merchantCommissionAmountTransfers,
        MerchantCommissionAmountTransfer $expectedMerchantCommissionAmountTransfer
    ): bool {
        foreach ($merchantCommissionAmountTransfers as $merchantCommissionAmountTransfer) {
            if ($merchantCommissionAmountTransfer->getIdMerchantCommissionAmountOrFail() === $expectedMerchantCommissionAmountTransfer->getIdMerchantCommissionAmountOrFail()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param int $idMerchantCommission
     * @param int $idStore
     *
     * @return bool
     */
    public function merchantCommissionStoreRelationExists(int $idMerchantCommission, int $idStore): bool
    {
        return $this->getMerchantCommissionStoreQuery()
            ->filterByFkMerchantCommission($idMerchantCommission)
            ->filterByFkStore($idStore)
            ->exists();
    }

    /**
     * @param int $idMerchantCommission
     * @param int $idMerchant
     *
     * @return bool
     */
    public function merchantCommissionMerchantRelationExists(int $idMerchantCommission, int $idMerchant): bool
    {
        return $this->getMerchantCommissionMerchantQuery()
            ->filterByFkMerchantCommission($idMerchantCommission)
            ->filterByFkMerchant($idMerchant)
            ->exists();
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $errorTransfers
     * @param string $expectedMessage
     *
     * @return void
     */
    public function assertValidationErrorsContainSingleMessageEqualTo(ArrayObject $errorTransfers, string $expectedMessage): void
    {
        $this->assertCount(1, $errorTransfers);
        $this->assertSame($expectedMessage, $errorTransfers->getIterator()->current()->getMessage());
    }

    /**
     * @return void
     */
    public function ensureMerchantCommissionDatabaseIsEmpty(): void
    {
        $this->ensureDatabaseTableIsEmpty($this->getMerchantCommissionQuery());
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
     * @return \Orm\Zed\MerchantCommission\Persistence\Base\SpyMerchantCommissionMerchantQuery
     */
    protected function getMerchantCommissionMerchantQuery(): SpyMerchantCommissionMerchantQuery
    {
        return SpyMerchantCommissionMerchantQuery::create();
    }
}
