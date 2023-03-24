<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PickingList\Communication\Plugin\Oms;

use Generated\Shared\Transfer\GeneratePickingListsRequestTransfer;
use Generated\Shared\Transfer\OrderItemFilterTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use Spryker\Zed\Oms\Dependency\Plugin\Command\CommandByOrderInterface;

/**
 * @method \Spryker\Zed\PickingList\Business\PickingListFacadeInterface getFacade()
 * @method \Spryker\Zed\PickingList\Communication\PickingListCommunicationFactory getFactory()
 * @method \Spryker\Zed\PickingList\PickingListConfig getConfig()
 */
class GeneratePickingListsCommandByOrderPlugin extends AbstractPlugin implements CommandByOrderInterface
{
    /**
     * {@inheritDoc}
     * - Groups order items by warehouse.
     * - Executes the stack of {@link \Spryker\Zed\PickingListExtension\Dependency\Plugin\PickingListGeneratorStrategyPluginInterface} plugins.
     * - Persists picking lists {@uses \Spryker\Zed\PickingList\Business\PickingListFacadeInterface::createPickingListCollection()}.
     *
     * @api
     *
     * @param list<\Orm\Zed\Sales\Persistence\SpySalesOrderItem> $orderItems
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     * @param \Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject $data
     *
     * @return list<\Generated\Shared\Transfer\PickingListTransfer>
     */
    public function run(array $orderItems, SpySalesOrder $orderEntity, ReadOnlyArrayObject $data)
    {
        $orderItemFilterTransfer = $this->createOrderItemFilterTransfer($orderItems);

        $itemCollectionTransfer = $this->getFactory()
            ->getSalesFacade()
            ->getOrderItems($orderItemFilterTransfer);

        /** @var \ArrayObject<int, \Generated\Shared\Transfer\ItemTransfer> $itemTransfers */
        $itemTransfers = $itemCollectionTransfer->getItems();

        $generatePickingListsRequestTransfer = $this->getFactory()
            ->createPickingListMapper()
            ->mapItemTransfersToGeneratePickingListsRequestTransfer(
                $itemTransfers,
                new GeneratePickingListsRequestTransfer(),
            );

        return (array)$this->getFacade()
            ->generatePickingLists($generatePickingListsRequestTransfer)
            ->getPickingLists();
    }

    /**
     * @param array<int, \Orm\Zed\Sales\Persistence\SpySalesOrderItem> $orderItems
     *
     * @return \Generated\Shared\Transfer\OrderItemFilterTransfer
     */
    protected function createOrderItemFilterTransfer(array $orderItems): OrderItemFilterTransfer
    {
        $orderItemFilterTransfer = new OrderItemFilterTransfer();
        foreach ($orderItems as $orderItem) {
            $orderItemFilterTransfer->addSalesOrderItemId($orderItem->getIdSalesOrderItem());
        }

        return $orderItemFilterTransfer;
    }
}
