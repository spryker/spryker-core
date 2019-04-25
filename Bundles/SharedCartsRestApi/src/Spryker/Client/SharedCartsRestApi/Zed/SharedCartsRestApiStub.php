<?php

namespace Spryker\Client\SharedCartsRestApi\Zed;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShareDetailCollectionTransfer;
use Spryker\Client\SharedCartsRestApi\Dependency\Client\SharedCartsRestApiToZedRequestClientInterface;

class SharedCartsRestApiStub implements SharedCartsRestApiStubInterface
{
    /**
     * @var \Spryker\Client\SharedCartsRestApi\Dependency\Client\SharedCartsRestApiToZedRequestClientInterface
     */
    protected $zedRequestClient;

    /**
     * @param \Spryker\Client\SharedCartsRestApi\Dependency\Client\SharedCartsRestApiToZedRequestClientInterface $zedRequestClient
     */
    public function __construct(SharedCartsRestApiToZedRequestClientInterface $zedRequestClient)
    {
        $this->zedRequestClient = $zedRequestClient;
    }

    /**
     * @param string $uuid
     *
     * @return \Generated\Shared\Transfer\ShareDetailCollectionTransfer
     */
    public function getSharedCartsByCartUuid(string $uuid): ShareDetailCollectionTransfer
    {
        $quoteTransfer = (new QuoteTransfer())->setUuid($uuid);
        /** @var \Generated\Shared\Transfer\ShareDetailCollectionTransfer $shareDetailCollectionTransfer */
        $shareDetailCollectionTransfer = $this->zedRequestClient->call(
            '/shared-carts-rest-api/gateway/get-shared-carts-by-cart-uuid',
            $quoteTransfer
        );

        return $shareDetailCollectionTransfer;
    }
}
