<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ResourceShare\ResourceShare;

use Generated\Shared\Transfer\ResourceShareResponseTransfer;

class ResourceShareExpander implements ResourceShareExpanderInterface
{
    /**
     * @var \Spryker\Client\ResourceShareExtension\Dependency\Plugin\ResourceShareResourceDataExpanderStrategyPluginInterface[]
     */
    protected $resourceShareResourceDataExpanderStrategyPlugins;

    /**
     * @param \Spryker\Client\ResourceShareExtension\Dependency\Plugin\ResourceShareResourceDataExpanderStrategyPluginInterface[] $resourceShareResourceDataExpanderStrategyPlugins
     */
    public function __construct(
        array $resourceShareResourceDataExpanderStrategyPlugins
    ) {
        $this->resourceShareResourceDataExpanderStrategyPlugins = $resourceShareResourceDataExpanderStrategyPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareResponseTransfer $resourceShareResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    public function executeResourceDataExpanderStrategyPlugins(ResourceShareResponseTransfer $resourceShareResponseTransfer): ResourceShareResponseTransfer
    {
        $resourceShareResponseTransfer->requireResourceShare();
        $resourceShareTransfer = $resourceShareResponseTransfer->getResourceShare();

        foreach ($this->resourceShareResourceDataExpanderStrategyPlugins as $resourceDataExpanderStrategyPlugin) {
            if (!$resourceDataExpanderStrategyPlugin->isApplicable($resourceShareTransfer)) {
                continue;
            }

            $resourceShareTransfer = $resourceDataExpanderStrategyPlugin->expand($resourceShareTransfer);
            break;
        }

        return $resourceShareResponseTransfer->setResourceShare($resourceShareTransfer);
    }
}
