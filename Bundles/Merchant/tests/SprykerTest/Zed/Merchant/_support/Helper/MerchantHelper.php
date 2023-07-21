<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Merchant\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\MerchantBuilder;
use Generated\Shared\DataBuilder\StoreRelationBuilder;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Merchant\Persistence\SpyMerchantQuery;
use Spryker\Zed\Merchant\MerchantConfig;
use SprykerTest\Shared\Store\Helper\StoreDataHelperTrait;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class MerchantHelper extends Module
{
    use DataCleanupHelperTrait;
    use LocatorHelperTrait;
    use StoreDataHelperTrait;

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    public function haveMerchant(array $seedData = []): MerchantTransfer
    {
        $merchantTransfer = $this->getMerchantTransfer($seedData);

        $merchantResponseTransfer = $this->getLocator()
            ->merchant()
            ->facade()
            ->createMerchant($merchantTransfer);
        $merchantTransfer = $merchantResponseTransfer->getMerchant();

        if (isset($seedData[MerchantTransfer::STATUS])) {
            $merchantTransfer->setStatus($seedData[MerchantTransfer::STATUS]);
        }

        if (isset($seedData[MerchantTransfer::IS_ACTIVE])) {
            $merchantTransfer->setIsActive($seedData[MerchantTransfer::IS_ACTIVE]);
        }

        if (isset($seedData[MerchantTransfer::IS_ACTIVE]) || isset($seedData[MerchantTransfer::STATUS])) {
            $merchantTransfer = $this->getLocator()
                ->merchant()
                ->facade()
                ->updateMerchant($merchantTransfer)
                ->getMerchant();
        }

        $this->getDataCleanupHelper()->_addCleanup(function () use ($merchantTransfer): void {
            $this->getMerchantQuery()->filterByIdMerchant($merchantTransfer->getIdMerchant())->delete();
        });

        return $merchantTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    public function haveMerchantWithStore(): MerchantTransfer
    {
        $storeRelationTransfer = $this->getStoreRelationTransfer();

        return $this->haveMerchant([MerchantTransfer::STORE_RELATION => $storeRelationTransfer->toArray()]);
    }

    /**
     * @return \Generated\Shared\Transfer\StoreRelationTransfer
     */
    public function getStoreRelationTransfer(): StoreRelationTransfer
    {
        $storeTransfer = $this->getStoreDataHelper()->haveStore([StoreTransfer::NAME => 'DE']);
        $storeRelationTransfer = (new StoreRelationBuilder())->seed([
            StoreRelationTransfer::ID_STORES => [$storeTransfer->getIdStore()],
        ])->build();

        return $storeRelationTransfer;
    }

    /**
     * @param int $idMerchant
     *
     * @return void
     */
    public function assertMerchantNotExists(int $idMerchant): void
    {
        $query = $this->getMerchantQuery()->filterByIdMerchant($idMerchant);
        $this->assertSame(0, $query->count());
    }

    /**
     * @return \Spryker\Zed\Merchant\MerchantConfig
     */
    public function createMerchantConfig(): MerchantConfig
    {
        return new MerchantConfig();
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    protected function addStoreRelation(MerchantTransfer $merchantTransfer, array $seedData): MerchantTransfer
    {
        $storeRelationTransfer = $this->createStoreRelationTransfer($seedData);
        $merchantTransfer->setStoreRelation($storeRelationTransfer->setIdEntity($merchantTransfer->getIdMerchant()));

        return $merchantTransfer;
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\StoreRelationTransfer
     */
    protected function createStoreRelationTransfer(array $seedData): StoreRelationTransfer
    {
        if (isset($seedData[MerchantTransfer::STORE_RELATION])) {
            return (new StoreRelationBuilder())->seed($seedData[MerchantTransfer::STORE_RELATION])->build();
        }

        return (new StoreRelationBuilder())->seed($seedData)->build();
    }

    /**
     * @return \Orm\Zed\Merchant\Persistence\SpyMerchantQuery
     */
    protected function getMerchantQuery(): SpyMerchantQuery
    {
        return SpyMerchantQuery::create();
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    public function getMerchantTransfer(array $seedData = []): MerchantTransfer
    {
        /** @var \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer */
        $merchantTransfer = (new MerchantBuilder($seedData))->build();
        $merchantTransfer->setIdMerchant(null);
        $merchantTransfer = $this->addStoreRelation($merchantTransfer, $seedData);

        return $merchantTransfer;
    }
}
