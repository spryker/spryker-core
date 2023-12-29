<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\Notifier;

use Generated\Shared\Transfer\OmsEventTriggeredTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;

class EventTriggeredNotifier implements EventTriggeredNotifierInterface
{
    /**
     * @var array<\Spryker\Zed\OmsExtension\Dependency\Plugin\OmsEventTriggeredListenerPluginInterface>
     */
    protected array $omsEventTriggeredListenerPlugins;

    /**
     * @param array<\Spryker\Zed\OmsExtension\Dependency\Plugin\OmsEventTriggeredListenerPluginInterface> $omsEventTriggeredListenerPlugins
     */
    public function __construct(array $omsEventTriggeredListenerPlugins)
    {
        $this->omsEventTriggeredListenerPlugins = $omsEventTriggeredListenerPlugins;
    }

    /**
     * @param string $idEvent
     * @param array<\Orm\Zed\Sales\Persistence\SpySalesOrderItem> $orderItems
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     * @param \Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject $data
     *
     * @return void
     */
    public function notifyOmsEventTriggeredListeners(string $idEvent, array $orderItems, SpySalesOrder $orderEntity, ReadOnlyArrayObject $data): void
    {
        $orderItemIds = array_map(function (SpySalesOrderItem $orderItem) {
            return $orderItem->getIdSalesOrderItem();
        }, $orderItems);

        $omsEventTriggeredTransfer = (new OmsEventTriggeredTransfer())
            ->setIdEvent($idEvent)
            ->setOrderItemIds($orderItemIds)
            ->setIdSalesOrder($orderEntity->getIdSalesOrder())
            ->setEventData($data->getArrayCopy());

        foreach ($this->omsEventTriggeredListenerPlugins as $omsEventTriggeredListener) {
            if ($omsEventTriggeredListener->isApplicable($omsEventTriggeredTransfer)) {
                $omsEventTriggeredListener->onEventTriggered($omsEventTriggeredTransfer);
            }
        }
    }
}
