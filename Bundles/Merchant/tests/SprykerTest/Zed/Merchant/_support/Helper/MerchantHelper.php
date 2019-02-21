<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Merchant\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\MerchantAddressBuilder;
use Generated\Shared\DataBuilder\MerchantAddressCollectionBuilder;
use Generated\Shared\DataBuilder\MerchantBuilder;
use Generated\Shared\Transfer\MerchantAddressCollectionTransfer;
use Generated\Shared\Transfer\MerchantAddressTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Orm\Zed\Merchant\Persistence\SpyMerchant;
use Orm\Zed\Merchant\Persistence\SpyMerchantAddressQuery;
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

        $merchantEntity = $this->createSpyMerchant();
        $merchantEntity->fromArray($merchantTransfer->setIdMerchant(null)->toArray());
        $merchantEntity->save();

        $merchantTransfer->setIdMerchant($merchantEntity->getIdMerchant());

        /** @var \SprykerTest\Shared\Testify\Helper\DataCleanupHelper $dataCleanupHelper */
        $dataCleanupHelper = $this->getDataCleanupHelper();
        $dataCleanupHelper->_addCleanup(function () use ($merchantTransfer) {
            $this->cleanupMerchant($merchantTransfer);
        });

        return $merchantTransfer;
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    public function haveMerchantWithAddressCollection(array $seedData = []): MerchantTransfer
    {
        /** @var \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer */
        $merchantTransfer = (new MerchantBuilder($seedData))->build();
        $merchantTransfer->setIdMerchant(null);
        $merchantAddressTransfer = (new MerchantAddressBuilder())->build();
        $merchantAddressCollection = (new MerchantAddressCollectionBuilder())->build();
        $merchantAddressCollection->addAddress($merchantAddressTransfer);
        $merchantTransfer->setAddressCollection($merchantAddressCollection);

        $merchantResponseTransfer = $this->getLocator()
            ->merchant()
            ->facade()
            ->createMerchant($merchantTransfer);
        $merchantTransfer = $merchantResponseTransfer->getMerchant();

        /** @var \SprykerTest\Shared\Testify\Helper\DataCleanupHelper $dataCleanupHelper */
        $dataCleanupHelper = $this->getDataCleanupHelper();
        $dataCleanupHelper->_addCleanup(function () use ($merchantTransfer, $merchantAddressTransfer) {
            $this->cleanupMerchant($merchantTransfer);
            $this->cleanupMerchantAddress($merchantAddressTransfer);
        });

        return $merchantTransfer;
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\MerchantAddressCollectionTransfer
     */
    public function haveMerchantAddressCollection(array $seedData = []): MerchantAddressCollectionTransfer
    {
        $merchantAddressTransfer = $this->haveMerchantAddress();
        $merchantAddressCollectionTransfer = new MerchantAddressCollectionTransfer();
        $merchantAddressCollectionTransfer = $merchantAddressCollectionTransfer->addAddress($merchantAddressTransfer);

        return $merchantAddressCollectionTransfer;
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\MerchantAddressTransfer
     */
    public function haveMerchantAddress(array $seedData = []): MerchantAddressTransfer
    {
        $merchantTransfer = $this->haveMerchant();

        /** @var \Generated\Shared\Transfer\MerchantAddressTransfer $merchantAddressTransfer */
        $merchantAddressTransfer = (new MerchantAddressBuilder($seedData))->build();
        $merchantAddressTransfer->setFkMerchant($merchantTransfer->getIdMerchant());

        $merchantAddressTransfer = $this->getLocator()
            ->merchant()
            ->facade()
            ->createMerchantAddress($merchantAddressTransfer);

        /** @var \SprykerTest\Shared\Testify\Helper\DataCleanupHelper $dataCleanupHelper */
        $dataCleanupHelper = $this->getDataCleanupHelper();
        $dataCleanupHelper->_addCleanup(function () use ($merchantAddressTransfer) {
            $this->cleanupMerchantAddress($merchantAddressTransfer);
        });

        return $merchantAddressTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return void
     */
    protected function cleanupMerchant(MerchantTransfer $merchantTransfer): void
    {
        $this->debug(sprintf('Deleting Merchant: %d', $merchantTransfer->getIdMerchant()));

        $this->getLocator()
            ->merchant()
            ->facade()
            ->deleteMerchant($merchantTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantAddressTransfer $merchantAddressTransfer
     *
     * @return void
     */
    protected function cleanupMerchantAddress(MerchantAddressTransfer $merchantAddressTransfer): void
    {
        $this->debug(sprintf('Deleting merchant address: %d', $merchantAddressTransfer->getIdMerchantAddress()));

        $this->getMerchantAddressQuery()
            ->findByIdMerchantAddress($merchantAddressTransfer->getIdMerchantAddress())
            ->delete();
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
     * @return \Orm\Zed\Merchant\Persistence\SpyMerchant
     */
    protected function createSpyMerchant(): SpyMerchant
    {
        return new SpyMerchant();
    }

    /**
     * @return \Orm\Zed\Merchant\Persistence\SpyMerchantQuery
     */
    protected function getMerchantQuery(): SpyMerchantQuery
    {
        return SpyMerchantQuery::create();
    }

    /**
     * @return \Orm\Zed\Merchant\Persistence\SpyMerchantAddressQuery
     */
    protected function getMerchantAddressQuery(): SpyMerchantAddressQuery
    {
        return SpyMerchantAddressQuery::create();
    }
}
