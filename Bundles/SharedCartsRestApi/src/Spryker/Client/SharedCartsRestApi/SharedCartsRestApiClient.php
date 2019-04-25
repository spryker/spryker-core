<?php

namespace Spryker\Client\SharedCartsRestApi;

use Generated\Shared\Transfer\ShareDetailCollectionTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\SharedCartsRestApi\SharedCartsRestApiFactory getFactory()
 */
class SharedCartsRestApiClient extends AbstractClient implements SharedCartsRestApiClientInterface
{
    /**
     * @param string $uuid
     *
     * @return \Generated\Shared\Transfer\ShareDetailCollectionTransfer
     */
    public function getSharedCartsByCartUuid(string $uuid): ShareDetailCollectionTransfer
    {
        return $this->getFactory()->createZedStub()->getSharedCartsByCartUuid($uuid);
    }
}
