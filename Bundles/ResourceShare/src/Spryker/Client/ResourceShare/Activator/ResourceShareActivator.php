<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ResourceShare\Activator;

use Generated\Shared\Transfer\ResourceShareRequestTransfer;
use Generated\Shared\Transfer\ResourceShareResponseTransfer;
use Spryker\Client\ResourceShare\Exception\ResourceShareActivatorStrategyNotFoundException;
use Spryker\Client\ResourceShare\Zed\ResourceShareStubInterface;

class ResourceShareActivator implements ResourceShareActivatorInterface
{
    /**
     * @var \Spryker\Client\ResourceShare\Zed\ResourceShareStubInterface
     */
    protected $zedResourceShareStub;

    /**
     * @var \Spryker\Client\ResourceShareExtension\Dependency\Plugin\ResourceShareActivatorStrategyPluginInterface[]
     */
    protected $resourceShareActivatorStrategyPlugins;

    /**
     * @param \Spryker\Client\ResourceShare\Zed\ResourceShareStubInterface $zedResourceShareStub
     * @param \Spryker\Client\ResourceShareExtension\Dependency\Plugin\ResourceShareActivatorStrategyPluginInterface[] $resourceShareActivatorStrategyPlugins
     */
    public function __construct(
        ResourceShareStubInterface $zedResourceShareStub,
        array $resourceShareActivatorStrategyPlugins
    ) {
        $this->zedResourceShareStub = $zedResourceShareStub;
        $this->resourceShareActivatorStrategyPlugins = $resourceShareActivatorStrategyPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareRequestTransfer $resourceShareRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    public function activateResourceShare(ResourceShareRequestTransfer $resourceShareRequestTransfer): ResourceShareResponseTransfer
    {
        $zedResourceShareResponseTransfer = $this->zedResourceShareStub->activateResourceShare($resourceShareRequestTransfer);
        if (!$zedResourceShareResponseTransfer->getIsSuccessful()) {
            return $zedResourceShareResponseTransfer;
        }

        $resourceShareRequestTransfer->setResourceShare($zedResourceShareResponseTransfer->getResourceShare());
        $strategyResourceShareResponseTransfer = $this->executeResourceShareActivatorStrategyPlugins($resourceShareRequestTransfer);

        return $this->getResourceShareResponseTransferWithCombinedMessages(
            $zedResourceShareResponseTransfer,
            $strategyResourceShareResponseTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareResponseTransfer $zedResourceShareResponseTransfer
     * @param \Generated\Shared\Transfer\ResourceShareResponseTransfer $strategyResourceShareResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    protected function getResourceShareResponseTransferWithCombinedMessages(
        ResourceShareResponseTransfer $zedResourceShareResponseTransfer,
        ResourceShareResponseTransfer $strategyResourceShareResponseTransfer
    ): ResourceShareResponseTransfer {
        $messageTransfers = $zedResourceShareResponseTransfer->getMessages();
        foreach ($strategyResourceShareResponseTransfer->getMessages() as $messageTransfer) {
            $messageTransfers->append($messageTransfer);
        }

        return $strategyResourceShareResponseTransfer->setMessages($messageTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareRequestTransfer $resourceShareRequestTransfer
     *
     * @throws \Spryker\Client\ResourceShare\Exception\ResourceShareActivatorStrategyNotFoundException
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    protected function executeResourceShareActivatorStrategyPlugins(
        ResourceShareRequestTransfer $resourceShareRequestTransfer
    ): ResourceShareResponseTransfer {
        foreach ($this->resourceShareActivatorStrategyPlugins as $resourceShareActivatorStrategyPlugin) {
            if (!$resourceShareActivatorStrategyPlugin->isApplicable($resourceShareRequestTransfer)) {
                continue;
            }

            return $resourceShareActivatorStrategyPlugin->execute($resourceShareRequestTransfer);
        }

        throw new ResourceShareActivatorStrategyNotFoundException(
            'Resource share activator strategy was not found. Please define one.'
        );
    }
}
