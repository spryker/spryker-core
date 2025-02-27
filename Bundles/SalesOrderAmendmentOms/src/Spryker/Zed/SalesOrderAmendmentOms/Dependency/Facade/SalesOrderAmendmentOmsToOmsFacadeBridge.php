<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendmentOms\Dependency\Facade;

use Generated\Shared\Transfer\OmsOrderItemStateTransfer;
use Generated\Shared\Transfer\OrderTransfer;

class SalesOrderAmendmentOmsToOmsFacadeBridge implements SalesOrderAmendmentOmsToOmsFacadeInterface
{
    /**
     * @var \Spryker\Zed\Oms\Business\OmsFacadeInterface
     */
    protected $omsFacade;

    /**
     * @param \Spryker\Zed\Oms\Business\OmsFacadeInterface $omsFacade
     */
    public function __construct($omsFacade)
    {
        $this->omsFacade = $omsFacade;
    }

    /**
     * @param string $eventId
     * @param list<int> $orderItemIds
     * @param array<string, mixed> $data
     *
     * @return array<mixed>|null
     */
    public function triggerEventForOrderItems(string $eventId, array $orderItemIds, array $data = []): ?array
    {
        return $this->omsFacade->triggerEventForOrderItems($eventId, $orderItemIds, $data);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param string $flag
     *
     * @return bool
     */
    public function areOrderItemsSatisfiedByFlag(OrderTransfer $orderTransfer, string $flag): bool
    {
        return $this->omsFacade->areOrderItemsSatisfiedByFlag($orderTransfer, $flag);
    }

    /**
     * @param string $stateName
     *
     * @return \Generated\Shared\Transfer\OmsOrderItemStateTransfer
     */
    public function getOmsOrderItemState(string $stateName): OmsOrderItemStateTransfer
    {
        return $this->omsFacade->getOmsOrderItemState($stateName);
    }
}
