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
use Orm\Zed\Merchant\Persistence\SpyMerchantQuery;
use Spryker\Zed\Merchant\MerchantConfig;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class MerchantHelper extends Module
{
    use DataCleanupHelperTrait;
    use LocatorHelperTrait;

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    public function haveMerchant(array $seedData = []): MerchantTransfer
    {
        /** @var \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer */
        $merchantTransfer = (new MerchantBuilder($seedData))->build();
        $merchantTransfer->setIdMerchant(null);
        $merchantTransfer = $this->addStoreRelation($merchantTransfer, $seedData);

        $merchantResponseTransfer = $this->getLocator()
            ->merchant()
            ->facade()
            ->createMerchant($merchantTransfer);
        $merchantTransfer = $merchantResponseTransfer->getMerchant();

        $this->getDataCleanupHelper()->_addCleanup(function () use ($merchantTransfer) {
            $this->getMerchantQuery()->filterByIdMerchant($merchantTransfer->getIdMerchant())->delete();
        });

        return $merchantTransfer;
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

        return (new StoreRelationBuilder())->build();
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
     * @return \Orm\Zed\Merchant\Persistence\SpyMerchantQuery
     */
    protected function getMerchantQuery(): SpyMerchantQuery
    {
        return SpyMerchantQuery::create();
    }
}
