<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ResourceShare\ResourceShare;

use Generated\Shared\Transfer\ResourceShareRequestTransfer;
use Generated\Shared\Transfer\ResourceShareResponseTransfer;
use Spryker\Client\ResourceShare\Zed\ResourceShareStubInterface;

class ResourceShareGenerator implements ResourceShareGeneratorInterface
{
    /**
     * @var \Spryker\Client\ResourceShare\Zed\ResourceShareStubInterface
     */
    protected $zedResourceShareStub;

    /**
     * @var \Spryker\Client\ResourceShare\ResourceShare\ResourceShareExpanderInterface
     */
    protected $resourceShareExpander;

    /**
     * @param \Spryker\Client\ResourceShare\Zed\ResourceShareStubInterface $zedResourceShareStub
     * @param \Spryker\Client\ResourceShare\ResourceShare\ResourceShareExpanderInterface $resourceShareExpander
     */
    public function __construct(
        ResourceShareStubInterface $zedResourceShareStub,
        ResourceShareExpanderInterface $resourceShareExpander
    ) {
        $this->zedResourceShareStub = $zedResourceShareStub;
        $this->resourceShareExpander = $resourceShareExpander;
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareRequestTransfer $resourceShareRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    public function generateResourceShare(ResourceShareRequestTransfer $resourceShareRequestTransfer): ResourceShareResponseTransfer
    {
        $resourceShareResponseTransfer = $this->zedResourceShareStub->generateResourceShare($resourceShareRequestTransfer);

        if (!$resourceShareResponseTransfer->getIsSuccessful()) {
            return $resourceShareResponseTransfer;
        }

        return $this->resourceShareExpander->executeResourceDataExpanderStrategyPlugins($resourceShareResponseTransfer);
    }
}
