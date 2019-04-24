<?php

namespace Spryker\Client\SharedCartsRestApi;

use Generated\Shared\Transfer\ShareDetailCollectionTransfer;

interface SharedCartsRestApiClientInterface
{
    /**
     * @param string $uuid
     *
     * @return \Generated\Shared\Transfer\ShareDetailCollectionTransfer
     */
    public function getSharedCartsByCartUuid($uuid): ShareDetailCollectionTransfer;
}
