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
     * @var \Spryker\Client\ResourceShareExtension\Dependency\Plugin\ResourceShareResourceDataExpanderStrategyPluginInterface[]
     */
    protected $resourceShareResourceDataExpanderStrategyPlugins;

    /**
     * @param \Spryker\Client\ResourceShare\Zed\ResourceShareStubInterface $zedResourceShareStub
     * @param array $resourceShareResourceDataExpanderStrategyPlugins
     */
    public function __construct(
        ResourceShareStubInterface $zedResourceShareStub,
        array $resourceShareResourceDataExpanderStrategyPlugins
    ) {
        $this->zedResourceShareStub = $zedResourceShareStub;
        $this->resourceShareResourceDataExpanderStrategyPlugins = $resourceShareResourceDataExpanderStrategyPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareRequestTransfer $resourceShareRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    public function generateResourceShare(ResourceShareRequestTransfer $resourceShareRequestTransfer
    ): ResourceShareResponseTransfer
    {
        $resourceShareResponseTransfer = $this->zedResourceShareStub->generateResourceShare($resourceShareRequestTransfer);
        if (!$resourceShareResponseTransfer->getIsSuccessful()) {
            return $resourceShareResponseTransfer;
        }

        return $this->executeResourceDataExpanderStrategyPlugins($resourceShareResponseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareResponseTransfer $resourceShareResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    protected function executeResourceDataExpanderStrategyPlugins(ResourceShareResponseTransfer $resourceShareResponseTransfer): ResourceShareResponseTransfer
    {
        $resourceShareTransfer = $resourceShareResponseTransfer->getResourceShare();
        foreach ($this->resourceShareResourceDataExpanderStrategyPlugins as $resourceDataExpanderStrategyPlugin) {
            if (!$resourceDataExpanderStrategyPlugin->isApplicable($resourceShareTransfer)) {
                continue;
            }

            $strategyResourceShareResponseTransfer = $resourceDataExpanderStrategyPlugin->expand($resourceShareTransfer);
            if (!$strategyResourceShareResponseTransfer->getIsSuccessful()) {
                return $strategyResourceShareResponseTransfer;
            }

            return $resourceShareResponseTransfer->setResourceShare($strategyResourceShareResponseTransfer->getResourceShare());
        }
    }
}
