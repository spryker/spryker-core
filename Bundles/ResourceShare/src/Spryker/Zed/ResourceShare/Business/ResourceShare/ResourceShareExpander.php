<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ResourceShare\Business\ResourceShare;

use Generated\Shared\Transfer\ResourceShareTransfer;

class ResourceShareExpander implements ResourceShareExpanderInterface
{
    /**
     * @var \Spryker\Zed\ResourceShareExtension\Dependency\Plugin\ResourceShareResourceDataExpanderStrategyPluginInterface[]
     */
    protected $resourceShareResourceDataExpanderStrategyPlugins;

    /**
     * @param \Spryker\Zed\ResourceShareExtension\Dependency\Plugin\ResourceShareResourceDataExpanderStrategyPluginInterface[] $resourceShareResourceDataExpanderStrategyPlugins
     */
    public function __construct(
        array $resourceShareResourceDataExpanderStrategyPlugins
    ) {
        $this->resourceShareResourceDataExpanderStrategyPlugins = $resourceShareResourceDataExpanderStrategyPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareTransfer $resourceShareTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareTransfer
     */
    public function executeResourceDataExpanderStrategyPlugins(
        ResourceShareTransfer $resourceShareTransfer
    ): ResourceShareTransfer {
        foreach ($this->resourceShareResourceDataExpanderStrategyPlugins as $resourceDataExpanderStrategyPlugin) {
            if (!$resourceDataExpanderStrategyPlugin->isApplicable($resourceShareTransfer)) {
                continue;
            }

            $resourceShareTransfer = $resourceDataExpanderStrategyPlugin->expand($resourceShareTransfer);
            break;
        }

        return $resourceShareTransfer;
    }
}
