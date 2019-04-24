<?php

namespace Spryker\Client\SharedCartsRestApi\Zed;

use Generated\Shared\Transfer\ShareDetailCollectionTransfer;

interface SharedCartsRestApiStubInterface
{
    /**
     * @param string $uuid
     *
     * @return \Generated\Shared\Transfer\ShareDetailCollectionTransfer
     */
    public function getSharedCartsByCartUuid(string $uuid): ShareDetailCollectionTransfer;
}
