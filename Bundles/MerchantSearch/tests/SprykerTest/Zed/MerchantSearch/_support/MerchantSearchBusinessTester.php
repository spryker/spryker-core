<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantSearch;

use Codeception\Actor;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\MerchantResponseTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Orm\Zed\MerchantSearch\Persistence\SpyMerchantSearchQuery;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class MerchantSearchBusinessTester extends Actor
{
    use _generated\MerchantSearchBusinessTesterActions;

    protected const MERCHANT_COUNT = 3;

    /**
     * @uses \Spryker\Zed\Merchant\MerchantConfig::STATUS_APPROVED
     */
    protected const MERCHANT_STATUS_APPROVED = 'approved';

    /**
     * @return void
     */
    public function setDependencies(): void
    {
        $this->setQueueAdaptersDependency();
    }

    /**
     * @return void
     */
    public function cleanUpDatabase(): void
    {
        $this->cleanUpMerchantSearchTable();
    }

    /**
     * @param int[] $ids
     *
     * @return \Generated\Shared\Transfer\EventEntityTransfer[]
     */
    public function createEventEntityTransfersFromIds(array $ids): array
    {
        $eventEntityTransfers = [];
        foreach ($ids as $id) {
            $eventEntityTransfers[] = (new EventEntityTransfer())->setId($id);
        }

        return $eventEntityTransfers;
    }

    /**
     * @param int[] $merchantIds
     *
     * @return int
     */
    public function getMerchantSearchCount(array $merchantIds): int
    {
        $merchantSearchQuery = $this->createMerchantSearchQuery();
        if (!$merchantIds) {
            return 0;
        }

        return $merchantSearchQuery->find()
            ->count();
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer[] $merchantTransfers
     *
     * @return int[]
     */
    public function extractMerchantIdsFromMerchantTransfers(array $merchantTransfers): array
    {
        return array_map(
            function (MerchantTransfer $merchantTransfer) {
                return $merchantTransfer->getIdMerchant();
            },
            $merchantTransfers
        );
    }

    /**
     * @param int $merchantCount
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer[]
     */
    public function createActiveMerchants(int $merchantCount = self::MERCHANT_COUNT): array
    {
        $merchantTransfers = [];
        for ($i = 0; $i < $merchantCount; $i++) {
            $merchantTransfers[] = $this->haveMerchant([MerchantTransfer::STATUS => static::MERCHANT_STATUS_APPROVED]);
        }
        $merchantIds = $this->extractMerchantIdsFromMerchantTransfers($merchantTransfers);
        $eventEntityTransfers = $this->createEventEntityTransfersFromIds($merchantIds);
        $this->getFacade()->writeCollectionByMerchantEvents($eventEntityTransfers);

        return $merchantTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantResponseTransfer
     */
    public function updateMerchant(MerchantTransfer $merchantTransfer): MerchantResponseTransfer
    {
        return $this->getLocator()
            ->merchant()
            ->facade()
            ->updateMerchant($merchantTransfer);
    }

    /**
     * @param int[] $merchantIds
     *
     * @return \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\MerchantSearch\Persistence\SpyMerchantSearch[]
     */
    public function getSynchronizationDataTransfersByMerchantIds(array $merchantIds): ObjectCollection
    {
        $merchantSearchQuery = $this->createMerchantSearchQuery();
        if ($merchantIds) {
            $merchantSearchQuery->filterByFkMerchant_In($merchantIds);
        }

        $merchantSearchEntityCollection = $merchantSearchQuery->setFormatter(ModelCriteria::FORMAT_OBJECT)
            ->find();

        return $merchantSearchEntityCollection;
    }

    /**
     * @return void
     */
    protected function setQueueAdaptersDependency(): void
    {
        $this->setDependency(
            QueueDependencyProvider::QUEUE_ADAPTERS,
            function (Container $container) {
                return [
                    $container->getLocator()->rabbitMq()->client()->createQueueAdapter(),
                ];
            }
        );
    }

    /**
     * @return void
     */
    protected function cleanUpMerchantSearchTable(): void
    {
        $this->createMerchantSearchQuery()->deleteAll();
    }

    /**
     * @return \Orm\Zed\MerchantSearch\Persistence\SpyMerchantSearchQuery
     */
    protected function createMerchantSearchQuery(): SpyMerchantSearchQuery
    {
        return SpyMerchantSearchQuery::create();
    }
}
