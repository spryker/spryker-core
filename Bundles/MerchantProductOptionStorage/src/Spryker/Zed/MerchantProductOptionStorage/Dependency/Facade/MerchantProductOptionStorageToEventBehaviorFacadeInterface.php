<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOptionStorage\Dependency\Facade;

interface MerchantProductOptionStorageToEventBehaviorFacadeInterface
{
    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return array<mixed>
     */
    public function getEventTransferIds(array $eventTransfers);
}
