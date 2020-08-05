<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOms\Persistence;

use Generated\Shared\Transfer\StateMachineItemTransfer;
use Orm\Zed\MerchantSalesOrder\Persistence\Map\SpyMerchantSalesOrderItemTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

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
        $merchantSalesOrderItemQuery = $this->getFactory()->getMerchantSalesOrderItemPropelQuery();

        $merchantSalesOrderItemEntities = $merchantSalesOrderItemQuery->filterByFkStateMachineItemState_In($stateIds)
            ->select([
                SpyMerchantSalesOrderItemTableMap::COL_ID_MERCHANT_SALES_ORDER_ITEM,
                SpyMerchantSalesOrderItemTableMap::COL_FK_STATE_MACHINE_ITEM_STATE,
            ])
            ->find();

        $stateMachineItemTransfers = [];

        foreach ($merchantSalesOrderItemEntities as $merchantSalesOrderItemEntity) {
            $stateMachineItemTransfers[] = (new StateMachineItemTransfer())
                ->setIdentifier($merchantSalesOrderItemEntity[SpyMerchantSalesOrderItemTableMap::COL_ID_MERCHANT_SALES_ORDER_ITEM])
                ->setIdItemState($merchantSalesOrderItemEntity[SpyMerchantSalesOrderItemTableMap::COL_FK_STATE_MACHINE_ITEM_STATE]);
        }

        return $stateMachineItemTransfers;
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
     * @module MerchantSalesOrder
     *
     * @param string $merchantOrderItemReference
     *
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer|null
     */
    public function findCurrentStateByMerchantOrderItemReference(string $merchantOrderItemReference): ?StateMachineItemTransfer
    {
        $merchantSalesOrderItemEntity = $this->getFactory()
            ->getMerchantSalesOrderItemPropelQuery()
            ->joinStateMachineItemState()
            ->findOneByMerchantOrderItemReference($merchantOrderItemReference);
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
}
