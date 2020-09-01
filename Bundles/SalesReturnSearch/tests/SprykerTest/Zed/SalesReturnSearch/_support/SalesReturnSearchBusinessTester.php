<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesReturnSearch;

use Codeception\Actor;
use Generated\Shared\Transfer\EventEntityTransfer;
use Orm\Zed\SalesReturnSearch\Persistence\SpySalesReturnReasonSearchQuery;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;

/**
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
 * @method \Spryker\Zed\SalesReturnSearch\Business\SalesReturnSearchFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class SalesReturnSearchBusinessTester extends Actor
{
    use _generated\SalesReturnSearchBusinessTesterActions;

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
        $this->cleanUpReturnReasonSearchTable();
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
     * @param int[] $returnReasonIds
     *
     * @return \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\SalesReturnSearch\Persistence\SpySalesReturnReasonSearch[]
     */
    public function getSalesReturnSearchEntitiesByReturnReasonIds(array $returnReasonIds = []): ObjectCollection
    {
        $spySalesReturnReasonSearchQuery = SpySalesReturnReasonSearchQuery::create();

        if ($returnReasonIds) {
            $spySalesReturnReasonSearchQuery->filterByFkSalesReturnReason_In($returnReasonIds);
        }

        return $spySalesReturnReasonSearchQuery->find();
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnReasonTransfer[] $returnReasonTransfers
     *
     * @return int[]
     */
    public function extractReturnReasonIdsFromReturnReasonTransfers(array $returnReasonTransfers): array
    {
        $salesReturnReasonIds = [];

        foreach ($returnReasonTransfers as $returnReasonTransfer) {
            $salesReturnReasonIds[] = $returnReasonTransfer->getIdSalesReturnReason();
        }

        return $salesReturnReasonIds;
    }

    /**
     * @return void
     */
    protected function setQueueAdaptersDependency(): void
    {
        $this->setDependency(QueueDependencyProvider::QUEUE_ADAPTERS, function (Container $container) {
            return [
                $container->getLocator()->rabbitMq()->client()->createQueueAdapter(),
            ];
        });
    }

    /**
     * @return void
     */
    protected function cleanUpReturnReasonSearchTable(): void
    {
        SpySalesReturnReasonSearchQuery::create()->deleteAll();
    }
}
