<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantSearch;

use Codeception\Actor;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Orm\Zed\MerchantSearch\Persistence\SpyMerchantSearchQuery;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

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

    public const MERCHANT_COUNT = 3;

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
     * @return \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\MerchantSearch\Persistence\SpyMerchantSearch[]
     */
    public function getMerchantEntitiesByMerchantIds(array $merchantIds): ObjectCollection
    {
        $merchantSearchQuery = SpyMerchantSearchQuery::create();
        if ($merchantIds) {
            $merchantSearchQuery->filterByFkMerchant($merchantIds, Criteria::IN);
        }

        return $merchantSearchQuery->find();
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
     * @return \Generated\Shared\Transfer\MerchantTransfer[]
     */
    public function createActiveMerchants(): array
    {
        $merchantTransfers = [];
        for ($i = 0; $i < static::MERCHANT_COUNT; $i++) {
            $merchantTransfer = $this->haveMerchant(
                [
                    MerchantTransfer::IS_ACTIVE => true,
                ]
            )->setStatus(static::MERCHANT_STATUS_APPROVED);
            $merchantResponseTransfer = $this->updateMerchant($merchantTransfer);
            $merchantTransfers[] = $merchantResponseTransfer->getMerchant();
        }
        $merchantIds = $this->extractMerchantIdsFromMerchantTransfers($merchantTransfers);
        $eventEntityTransfers = $this->createEventEntityTransfersFromIds($merchantIds);
        $this->getFacade()->writeCollectionByMerchantEvents($eventEntityTransfers);

        return $merchantTransfers;
    }

    /**
     * @param int[] $merchantIds
     *
     * @return \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\MerchantSearch\Persistence\SpyMerchantSearch[]
     */
    public function getSynchronizationDataTransfersByMerchantIds(array $merchantIds)
    {
        $merchantSearchQuery = SpyMerchantSearchQuery::create();
        if ($merchantIds) {
            $merchantSearchQuery->filterByFkMerchant($merchantIds, Criteria::IN);
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
        SpyMerchantSearchQuery::create()->deleteAll();
    }
}
