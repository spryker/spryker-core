<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOms\Persistence;

use Generated\Shared\Transfer\StateMachineItemTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\MerchantOms\Persistence\MerchantOmsPersistenceFactory getFactory()
 */
class MerchantOmsRepository extends AbstractRepository implements MerchantOmsRepositoryInterface
{
    /**
     * @param array $stateIds
     *
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer[]
     */
    public function getStateMachineItemsByStateIds(array $stateIds): array
    {
        $merchantSalesOrderItemEntities = $this->getFactory()
            ->getMerchantSalesOrderItemPropelQuery()
            ->joinWithStateMachineItemState()
            ->useStateMachineItemStateQuery()
                ->joinWithProcess()
            ->endUse()
            ->filterByFkStateMachineItemState_In($stateIds)
            ->find();

        return $this->getFactory()->createMerchantOmsMapper()->mapMerchantSalesOrderItemEntityCollectionToStateMachineItemTransfers(
            $merchantSalesOrderItemEntities
        );
    }

    /**
     * @module StateMachine
     * @module MerchantSalesOrder
     *
     * @param int $idSalesOrderItem
     *
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer|null
     */
    public function findCurrentStateByIdSalesOrderItem(int $idSalesOrderItem): ?StateMachineItemTransfer
    {
        $merchantSalesOrderItemEntity = $this->getFactory()->getMerchantSalesOrderItemPropelQuery()
            ->joinStateMachineItemState()
            ->findOneByFkSalesOrderItem($idSalesOrderItem);
        if ($merchantSalesOrderItemEntity === null) {
            return null;
        }

        return $this->getFactory()
            ->createStateMachineItemMapper()
            ->mapStateMachineItemEntityToStateMachineItemTransfer(
                $merchantSalesOrderItemEntity->getStateMachineItemState(),
                (new StateMachineItemTransfer())
            );
    }

    /**
     * @module StateMachine
     *
     * @param int[] $merchantOrderItemIds
     *
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer[]
     */
    public function findStateHistoryByMerchantOrderIds(array $merchantOrderItemIds): array
    {
        $stateMachineItemTransfers = [];

        $stateMachineItemStateHistoryEntities = $this->getFactory()
            ->getStateMachineItemStateHistoryPropelQuery()
            ->joinState()
            ->filterByIdentifier_In($merchantOrderItemIds)
            ->orderByCreatedAt(Criteria::DESC)
            ->find();

        foreach ($stateMachineItemStateHistoryEntities as $stateMachineItemStateHistoryEntity) {
            $stateMachineItemTransfer = $this->getFactory()
                ->createStateMachineItemMapper()
                ->mapStateMachineItemStateHistoryEntityToStateMachineItemTransfer(
                    $stateMachineItemStateHistoryEntity,
                    new StateMachineItemTransfer()
                );

            $stateMachineItemTransfers[] = $stateMachineItemTransfer;
        }

        return $stateMachineItemTransfers;
    }
}
