<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SharedCartsRestApi\Zed;

use Generated\Shared\Transfer\ShareCartRequestTransfer;
use Generated\Shared\Transfer\ShareCartResponseTransfer;
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
     * @param \Generated\Shared\Transfer\ShareCartRequestTransfer $shareCartRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShareCartResponseTransfer
     */
    public function create(ShareCartRequestTransfer $shareCartRequestTransfer): ShareCartResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\ShareCartResponseTransfer $shareCartResponseTransfer */
        $shareCartResponseTransfer = $this->zedRequestClient->call(
            '/shared-carts-rest-api/gateway/create',
            $shareCartRequestTransfer
        );

        return $shareCartResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShareCartRequestTransfer $shareCartRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShareCartResponseTransfer
     */
    public function update(ShareCartRequestTransfer $shareCartRequestTransfer): ShareCartResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\ShareCartResponseTransfer $shareCartResponseTransfer */
        $shareCartResponseTransfer = $this->zedRequestClient->call(
            '/shared-carts-rest-api/gateway/update',
            $shareCartRequestTransfer
        );

        return $shareCartResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShareCartRequestTransfer $shareCartRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShareCartResponseTransfer
     */
    public function delete(ShareCartRequestTransfer $shareCartRequestTransfer): ShareCartResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\ShareCartResponseTransfer $shareCartResponseTransfer */
        $shareCartResponseTransfer = $this->zedRequestClient->call(
            '/shared-carts-rest-api/gateway/delete',
            $shareCartRequestTransfer
        );

        return $shareCartResponseTransfer;
    }
}
