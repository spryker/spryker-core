<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ResourceShare\Business\ResourceShare;

use Generated\Shared\Transfer\ResourceShareRequestTransfer;
use Generated\Shared\Transfer\ResourceShareResponseTransfer;

class ResourceShareActivator implements ResourceShareActivatorInterface
{
    /**
     * @var \Spryker\Zed\ResourceShare\Business\ResourceShare\ResourceShareReaderInterface
     */
    protected $resourceShareReader;

    /**
     * @var \Spryker\Zed\ResourceShareExtension\Dependency\Plugin\ResourceShareZedActivatorStrategyPluginInterface[]
     */
    protected $resourceShareActivatorStrategyPlugins;

    /**
     * @param \Spryker\Zed\ResourceShare\Business\ResourceShare\ResourceShareReaderInterface $resourceShareReader
     * @param \Spryker\Zed\ResourceShareExtension\Dependency\Plugin\ResourceShareZedActivatorStrategyPluginInterface[] $resourceShareActivatorStrategyPlugins
     */
    public function __construct(
        ResourceShareReaderInterface $resourceShareReader,
        array $resourceShareActivatorStrategyPlugins
    ) {
        $this->resourceShareReader = $resourceShareReader;
        $this->resourceShareActivatorStrategyPlugins = $resourceShareActivatorStrategyPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareRequestTransfer $resourceShareRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    public function activateResourceShare(
        ResourceShareRequestTransfer $resourceShareRequestTransfer
    ): ResourceShareResponseTransfer {
        return $this->executeResourceShareActivatorStrategyPlugins(
            $resourceShareRequestTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareRequestTransfer $resourceShareRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    protected function executeResourceShareActivatorStrategyPlugins(
        ResourceShareRequestTransfer $resourceShareRequestTransfer
    ): ResourceShareResponseTransfer {
        $resourceShareResponseTransfer = (new ResourceShareResponseTransfer())
            ->setIsSuccessful(true)
            ->setResourceShare($resourceShareRequestTransfer->getResourceShare());

        foreach ($this->resourceShareActivatorStrategyPlugins as $resourceShareActivatorStrategyPlugin) {
            if (!$resourceShareActivatorStrategyPlugin->isApplicable($resourceShareRequestTransfer)) {
                continue;
            }

            $resourceShareResponseTransfer = $resourceShareActivatorStrategyPlugin->execute($resourceShareRequestTransfer);
            break;
        }

        return $resourceShareResponseTransfer;
    }
}
