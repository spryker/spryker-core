<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Communication\Collector;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ReclamationTransfer;

interface OmsTriggerFormCollectorInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param string[] $events
     *
     * @return \Symfony\Component\Form\FormView[]
     */
    public function buildOrderOmsTriggerFormCollection(OrderTransfer $orderTransfer, array $events): array;

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param array $eventsGroupedByItem
     *
     * @return array
     */
    public function buildOrderItemOmsTriggerFormCollection(ItemTransfer $itemTransfer, array $eventsGroupedByItem): array;

    /**
     * @param \Generated\Shared\Transfer\ReclamationTransfer $reclamationTransfer
     * @param array $events
     *
     * @return array
     */
    public function buildReclamationOmsTriggerFormCollection(ReclamationTransfer $reclamationTransfer, array $events): array;

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param array $eventsGroupedByItem
     * @param \Generated\Shared\Transfer\ReclamationTransfer $reclamationTransfer
     *
     * @return array
     */
    public function buildReclamationItemOmsTriggerFormCollection(
        ItemTransfer $itemTransfer,
        array $eventsGroupedByItem,
        ReclamationTransfer $reclamationTransfer
    ): array;
}
