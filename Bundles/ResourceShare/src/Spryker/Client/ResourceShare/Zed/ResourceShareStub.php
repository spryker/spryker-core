<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ResourceShare\Zed;

use Everon\Component\Factory\Tests\Unit\Doubles\AbstractStub;
use Generated\Shared\Transfer\ResourceShareRequestTransfer;
use Generated\Shared\Transfer\ResourceShareResponseTransfer;
use Spryker\Client\ResourceShare\Dependency\Client\ResourceShareToZedRequestClientInterface;

class ResourceShareStub extends AbstractStub implements ResourceShareStubInterface
{
    /**
     * @var \Spryker\Client\ResourceShare\Dependency\Client\ResourceShareToZedRequestClientInterface
     */
    protected $zedRequestClient;

    /**
     * @param \Spryker\Client\ResourceShare\Dependency\Client\ResourceShareToZedRequestClientInterface $zedRequestClient
     */
    public function __construct(ResourceShareToZedRequestClientInterface $zedRequestClient)
    {
        $this->zedRequestClient = $zedRequestClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareRequestTransfer $resourceShareRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    public function generateResourceShare(ResourceShareRequestTransfer $resourceShareRequestTransfer): ResourceShareResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\ResourceShareResponseTransfer $resourceShareResponseTransfer */
        $resourceShareResponseTransfer = $this->zedRequestClient->call('/resource-share/gateway/generate-resource-share', $resourceShareRequestTransfer);

        return $resourceShareResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareRequestTransfer $resourceShareRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    public function activateResourceShare(ResourceShareRequestTransfer $resourceShareRequestTransfer): ResourceShareResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\ResourceShareResponseTransfer $resourceShareResponseTransfer */
        $resourceShareResponseTransfer = $this->zedRequestClient->call('/resource-share/gateway/activate-resource-share', $resourceShareRequestTransfer);

        return $resourceShareResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareRequestTransfer $resourceShareRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    public function getResourceShareByUuid(ResourceShareRequestTransfer $resourceShareRequestTransfer): ResourceShareResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\ResourceShareResponseTransfer $resourceShareResponseTransfer */
        $resourceShareResponseTransfer = $this->zedRequestClient->call('/resource-share/gateway/get-resource-share-by-uuid', $resourceShareRequestTransfer);

        return $resourceShareResponseTransfer;
    }
}
