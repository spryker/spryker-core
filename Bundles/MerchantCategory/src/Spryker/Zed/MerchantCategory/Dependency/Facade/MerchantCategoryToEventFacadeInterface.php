<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCategory\Dependency\Facade;

interface MerchantCategoryToEventFacadeInterface
{
    /**
     * @param string $eventName
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $transfers
     *
     * @return void
     */
    public function triggerBulk($eventName, array $transfers): void;
}
