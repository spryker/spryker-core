<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlock\Dependency\Facade;

interface CmsSlotBlockToEventFacadeInterface
{
    /**
     * @param string $eventName
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $transfers
     *
     * @return void
     */
    public function triggerBulk($eventName, array $transfers): void;
}
