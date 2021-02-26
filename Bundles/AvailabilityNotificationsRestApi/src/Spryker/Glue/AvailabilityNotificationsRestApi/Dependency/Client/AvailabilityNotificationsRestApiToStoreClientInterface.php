<?php

namespace Spryker\Glue\AvailabilityNotificationsRestApi\Dependency\Client;

use Generated\Shared\Transfer\StoreTransfer;

interface AvailabilityNotificationsRestApiToStoreClientInterface
{
    /**
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getCurrentStore(): StoreTransfer;
}
