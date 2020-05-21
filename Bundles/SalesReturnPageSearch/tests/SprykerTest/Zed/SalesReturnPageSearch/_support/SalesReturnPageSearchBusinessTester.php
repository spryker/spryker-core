<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesReturnPageSearch;

use Codeception\Actor;
use Generated\Shared\Transfer\EventEntityTransfer;
use Orm\Zed\SalesReturnPageSearch\Persistence\SpySalesReturnReasonPageSearchQuery;
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
 * @method \Spryker\Zed\SalesReturnPageSearch\Business\SalesReturnPageSearchFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class SalesReturnPageSearchBusinessTester extends Actor
{
    use _generated\SalesReturnPageSearchBusinessTesterActions;

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
        $this->cleanUpConfigurableBundlePageSearchTable();
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
     * @return \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\SalesReturnPageSearch\Persistence\SpySalesReturnReasonPageSearch[]
     */
    public function getSalesReturnPageSearchEntitiesByReturnReasonIds(array $returnReasonIds = []): ObjectCollection
    {
        $spySalesReturnReasonPageSearchQuery = SpySalesReturnReasonPageSearchQuery::create();

        if ($returnReasonIds) {
            $spySalesReturnReasonPageSearchQuery->filterByFkSalesReturnReason_In($returnReasonIds);
        }

        return $spySalesReturnReasonPageSearchQuery->find();
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
    protected function cleanUpConfigurableBundlePageSearchTable(): void
    {
        SpySalesReturnReasonPageSearchQuery::create()->deleteAll();
    }
}
