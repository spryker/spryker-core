<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ResourceShare\ResourceShareActivator;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ResourceShareRequestTransfer;
use Generated\Shared\Transfer\ResourceShareResponseTransfer;
use Spryker\Client\ResourceShare\Zed\ResourceShareStubInterface;

class ResourceShareActivator implements ResourceShareActivatorInterface
{
    /**
     * @var \Spryker\Client\ResourceShare\Zed\ResourceShareStubInterface
     */
    protected $zedResourceShareStub;

    /**
     * @param \Spryker\Client\ResourceShare\Zed\ResourceShareStubInterface $zedResourceShareStub
     */
    public function __construct(ResourceShareStubInterface $zedResourceShareStub)
    {
        $this->zedResourceShareStub = $zedResourceShareStub;
    }

    /**
     * @param string $uuid
     * @param \Generated\Shared\Transfer\CustomerTransfer|null $customerTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    public function activateResourceShare(
        string $uuid,
        ?CustomerTransfer $customerTransfer
    ): ResourceShareResponseTransfer {
        $resourceShareRequestTransfer = (new ResourceShareRequestTransfer())
            ->setUuid($uuid)
            ->setCustomer($customerTransfer);

        return $this->zedResourceShareStub->activateResourceShare($resourceShareRequestTransfer);
    }
}
