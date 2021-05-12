<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOms\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\StateMachineItemTransfer;
use Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderItem;
use Propel\Runtime\Collection\ObjectCollection;

class MerchantOmsMapper
{
    /**
     * @phpstan-param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderItem> $merchantSalesOrderItemEntities
     *
     * @param \Propel\Runtime\Collection\ObjectCollection $merchantSalesOrderItemEntities
     *
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer[]
     */
    public function mapMerchantSalesOrderItemEntityCollectionToStateMachineItemTransfers(
        ObjectCollection $merchantSalesOrderItemEntities
    ): array {
        $stateMachineItemTransfers = [];
        foreach ($merchantSalesOrderItemEntities as $merchantSalesOrderItemEntity) {
            $stateMachineItemTransfer = $this->mapMerchantSalesOrderItemEntityToStateMachineItemTransfer(
                $merchantSalesOrderItemEntity,
                new StateMachineItemTransfer()
            );

            $stateMachineItemTransfers[] = $stateMachineItemTransfer;
        }

        return $stateMachineItemTransfers;
    }

    /**
     * @param \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderItem $merchantSalesOrderItemEntity
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer $stateMachineItemTransfer
     *
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer
     */
    protected function mapMerchantSalesOrderItemEntityToStateMachineItemTransfer(
        SpyMerchantSalesOrderItem $merchantSalesOrderItemEntity,
        StateMachineItemTransfer $stateMachineItemTransfer
    ): StateMachineItemTransfer {
        /** @var \Orm\Zed\StateMachine\Persistence\SpyStateMachineItemState $stateMachineItemStateEntity */
        $stateMachineItemStateEntity = $merchantSalesOrderItemEntity->getStateMachineItemState();

        $stateMachineItemTransfer = $stateMachineItemTransfer->fromArray($merchantSalesOrderItemEntity->toArray(), true)
            ->setIdentifier($merchantSalesOrderItemEntity->getIdMerchantSalesOrderItem())
            ->setIdItemState($merchantSalesOrderItemEntity->getFkStateMachineItemState())
            ->setStateName($stateMachineItemStateEntity->getName())
            ->setProcessName($stateMachineItemStateEntity->getProcess()->getName())
            ->setStateMachineName($stateMachineItemStateEntity->getProcess()->getStateMachineName());

        return $stateMachineItemTransfer;
    }
}
