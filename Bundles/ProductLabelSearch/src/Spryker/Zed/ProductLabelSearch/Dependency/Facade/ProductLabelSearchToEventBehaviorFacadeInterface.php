<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelSearch\Dependency\Facade;

use Generated\Shared\Transfer\HydrateEventsRequestTransfer;
use Generated\Shared\Transfer\HydrateEventsResponseTransfer;

interface ProductLabelSearchToEventBehaviorFacadeInterface
{
    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return array
     */
    public function getEventTransferIds(array $eventTransfers);

    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     * @param string $foreignKeyColumnName
     *
     * @return array
     */
    public function getEventTransferForeignKeys(array $eventTransfers, $foreignKeyColumnName);

    /**
     * @param \Generated\Shared\Transfer\HydrateEventsRequestTransfer $hydrateEventsRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HydrateEventsResponseTransfer
     */
    public function hydrateEventDataTransfer(HydrateEventsRequestTransfer $hydrateEventsRequestTransfer): HydrateEventsResponseTransfer;
}
