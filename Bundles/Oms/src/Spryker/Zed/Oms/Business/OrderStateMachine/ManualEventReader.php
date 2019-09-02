<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\OrderStateMachine;

use Spryker\Zed\Oms\Persistence\OmsRepositoryInterface;

class ManualEventReader implements ManualEventReaderInterface
{
    /**
     * @var \Spryker\Zed\Oms\Persistence\OmsRepositoryInterface
     */
    protected $omsRepository;

    /**
     * @var \Spryker\Zed\Oms\Business\OrderStateMachine\OrderItemManualEventReaderInterface
     */
    protected $orderItemManualEventReader;

    /**
     * @var \Spryker\Zed\OmsExtension\Dependency\Plugin\OmsManualEventGrouperPluginInterface[] $eventGrouperPlugins
     */
    protected $eventGrouperPlugins;

    /**
     * @param \Spryker\Zed\Oms\Persistence\OmsRepositoryInterface $omsRepository
     * @param \Spryker\Zed\Oms\Business\OrderStateMachine\OrderItemManualEventReaderInterface $orderItemManualEventReader
     * @param \Spryker\Zed\OmsExtension\Dependency\Plugin\OmsManualEventGrouperPluginInterface[] $eventGrouperPlugins
     */
    public function __construct(
        OmsRepositoryInterface $omsRepository,
        OrderItemManualEventReaderInterface $orderItemManualEventReader,
        array $eventGrouperPlugins
    ) {
        $this->omsRepository = $omsRepository;
        $this->orderItemManualEventReader = $orderItemManualEventReader;
        $this->eventGrouperPlugins = $eventGrouperPlugins;
    }

    /**
     * @param int $idSalesOrder
     *
     * @return string[]
     */
    public function getDistinctManualEventsByIdSalesOrderGroupedByShipment(int $idSalesOrder): array
    {
        $itemTransfers = $this->omsRepository->getSalesOrderItemsByIdSalesOrder($idSalesOrder);

        $events = $this->orderItemManualEventReader->getManualEventsByIdSalesOrder($itemTransfers);

        return $this->getManualEventsGroupingUsingPlugins($events, $itemTransfers);
    }

    /**
     * @param array $events
     * @param \Generated\Shared\Transfer\ItemTransfer[] $orderItemTransfers
     *
     * @return string[]
     */
    protected function getManualEventsGroupingUsingPlugins(array $events, array $orderItemTransfers): array
    {
        foreach ($this->eventGrouperPlugins as $eventGrouperPlugin) {
            $events = $eventGrouperPlugin->group($events, $orderItemTransfers);
        }

        return $events;
    }
}
