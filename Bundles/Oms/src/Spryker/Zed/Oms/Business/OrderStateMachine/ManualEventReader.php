<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\OrderStateMachine;

use ArrayObject;
use Spryker\Zed\Oms\Dependency\Facade\OmsToSalesInterface;

class ManualEventReader implements ManualEventReaderInterface
{
    /**
     * @var \Spryker\Zed\Oms\Business\OrderStateMachine\OrderItemManualEventReaderInterface
     */
    protected $orderItemManualEventReader;

    /**
     * @var \Spryker\Zed\OmsExtension\Dependency\Plugin\OmsManualEventGrouperPluginInterface[]
     */
    protected $eventGrouperPlugins;

    /**
     * @var \Spryker\Zed\Oms\Dependency\Facade\OmsToSalesInterface
     */
    protected $salesFacade;

    /**
     * @param \Spryker\Zed\Oms\Business\OrderStateMachine\OrderItemManualEventReaderInterface $orderItemManualEventReader
     * @param \Spryker\Zed\OmsExtension\Dependency\Plugin\OmsManualEventGrouperPluginInterface[] $eventGrouperPlugins
     * @param \Spryker\Zed\Oms\Dependency\Facade\OmsToSalesInterface $salesFacade
     */
    public function __construct(
        OrderItemManualEventReaderInterface $orderItemManualEventReader,
        array $eventGrouperPlugins,
        OmsToSalesInterface $salesFacade
    ) {
        $this->orderItemManualEventReader = $orderItemManualEventReader;
        $this->eventGrouperPlugins = $eventGrouperPlugins;
        $this->salesFacade = $salesFacade;
    }

    /**
     * @param int $idSalesOrder
     *
     * @return string[]
     */
    public function getDistinctManualEventsByIdSalesOrderGroupedByShipment(int $idSalesOrder): array
    {
        $orderTransfer = $this->salesFacade->getOrderByIdSalesOrder($idSalesOrder);
        if ($orderTransfer === null) {
            return [];
        }

        $itemTransfers = $orderTransfer->getItems();
        $events = $this->orderItemManualEventReader->getManualEventsByIdSalesOrder($itemTransfers);

        return $this->getManualEventsGroupingUsingPlugins($events, $itemTransfers);
    }

    /**
     * @param array $events
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $orderItemTransfers
     *
     * @return string[]
     */
    protected function getManualEventsGroupingUsingPlugins(array $events, ArrayObject $orderItemTransfers): array
    {
        foreach ($this->eventGrouperPlugins as $eventGrouperPlugin) {
            $events = $eventGrouperPlugin->group($events, $orderItemTransfers);
        }

        return $events;
    }
}
