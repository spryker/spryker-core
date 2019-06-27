<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Communication\Collector;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ReclamationTransfer;
use Spryker\Zed\Oms\Communication\Factory\OmsTriggerFormFactoryInterface;

class OmsTriggerFormCollector implements OmsTriggerFormCollectorInterface
{
    /**
     * @var \Spryker\Zed\Oms\Communication\Factory\OmsTriggerFormFactoryInterface
     */
    protected $omsTriggerFormFactory;

    /**
     * @param \Spryker\Zed\Oms\Communication\Factory\OmsTriggerFormFactoryInterface $omsTriggerFormFactory
     */
    public function __construct(OmsTriggerFormFactoryInterface $omsTriggerFormFactory)
    {
        $this->omsTriggerFormFactory = $omsTriggerFormFactory;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param string[] $events
     *
     * @return \Symfony\Component\Form\FormView[]
     */
    public function buildOrderOmsTriggerFormCollection(OrderTransfer $orderTransfer, array $events): array
    {
        $orderOmsTriggerFormCollection = [];

        foreach ($events as $event) {
            $orderOmsTriggerFormCollection[$event] = $this->omsTriggerFormFactory
                ->getOrderOmsTriggerForm($orderTransfer, $event)
                ->createView();
        }

        return $orderOmsTriggerFormCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param array $eventsGroupedByItem
     *
     * @return array
     */
    public function buildOrderItemOmsTriggerFormCollection(ItemTransfer $itemTransfer, array $eventsGroupedByItem): array
    {
        $orderItemOmsTriggerFormCollection = [];

        foreach ($eventsGroupedByItem as $event) {
            $orderItemOmsTriggerFormCollection[$event] = $this->omsTriggerFormFactory
                ->getOrderItemOmsTriggerForm($itemTransfer, $event)
                ->createView();
        }

        return $orderItemOmsTriggerFormCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\ReclamationTransfer $reclamationTransfer
     * @param array $events
     *
     * @return array
     */
    public function buildReclamationOmsTriggerFormCollection(ReclamationTransfer $reclamationTransfer, array $events): array
    {
        $reclamationOmsTriggerFormCollection = [];

        foreach ($events as $event) {
            $reclamationOmsTriggerFormCollection[$event] = $this->omsTriggerFormFactory
                ->getReclamationOmsTriggerForm($reclamationTransfer, $event)
                ->createView();
        }

        return $reclamationOmsTriggerFormCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param array $eventsGroupedByItem
     * @param \Generated\Shared\Transfer\ReclamationTransfer $reclamationTransfer
     *
     * @return array
     */
    public function buildReclamationItemOmsTriggerFormCollection(ItemTransfer $itemTransfer, array $eventsGroupedByItem, ReclamationTransfer $reclamationTransfer): array
    {
        $orderItemOmsTriggerFormCollection = [];

        foreach ($eventsGroupedByItem as $event) {
            $orderItemOmsTriggerFormCollection[$event] = $this->omsTriggerFormFactory
                ->getReclamationItemOmsTriggerForm(
                    $itemTransfer,
                    $event,
                    $reclamationTransfer->getIdSalesReclamation()
                )
                ->createView();
        }

        return $orderItemOmsTriggerFormCollection;
    }
}
