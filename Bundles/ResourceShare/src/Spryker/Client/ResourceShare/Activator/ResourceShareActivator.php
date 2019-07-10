<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ResourceShare\Activator;

use ArrayObject;
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
     * @var \Spryker\Client\ResourceShareExtension\Dependency\Plugin\ResourceShareClientActivatorStrategyPluginInterface[]
     */
    protected $beforeZedResourceShareActivatorStrategyPlugins;

    /**
     * @var array|\Spryker\Client\ResourceShareExtension\Dependency\Plugin\ResourceShareClientActivatorStrategyPluginInterface[]
     */
    protected $afterZedResourceShareActivatorStrategyPlugins;

    /**
     * @param \Spryker\Client\ResourceShare\Zed\ResourceShareStubInterface $zedResourceShareStub
     * @param \Spryker\Client\ResourceShareExtension\Dependency\Plugin\ResourceShareClientActivatorStrategyPluginInterface[] $beforeZedResourceShareActivatorStrategyPlugins
     * @param \Spryker\Client\ResourceShareExtension\Dependency\Plugin\ResourceShareClientActivatorStrategyPluginInterface[] $afterZedResourceShareActivatorStrategyPlugins
     */
    public function __construct(
        ResourceShareStubInterface $zedResourceShareStub,
        array $beforeZedResourceShareActivatorStrategyPlugins,
        array $afterZedResourceShareActivatorStrategyPlugins
    ) {
        $this->zedResourceShareStub = $zedResourceShareStub;
        $this->beforeZedResourceShareActivatorStrategyPlugins = $beforeZedResourceShareActivatorStrategyPlugins;
        $this->afterZedResourceShareActivatorStrategyPlugins = $afterZedResourceShareActivatorStrategyPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareRequestTransfer $resourceShareRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    public function activateResourceShare(ResourceShareRequestTransfer $resourceShareRequestTransfer): ResourceShareResponseTransfer
    {
        $resourceShareResponseTransfer = $this->executeBeforeZedClientActivatros($resourceShareRequestTransfer);
        if (!$resourceShareResponseTransfer->getIsSuccessful()) {
            return $resourceShareResponseTransfer;
        }

        $resourceShareResponseTransfer = $this->executeZedCallResourceShareActivators(
            $resourceShareRequestTransfer->setResourceShare($resourceShareResponseTransfer->getResourceShare()),
            $resourceShareResponseTransfer->getMessages()
        );
        if (!$resourceShareResponseTransfer->getIsSuccessful()) {
            return $resourceShareResponseTransfer;
        }

        return $this->executeAfterZedClientActivators(
            $resourceShareRequestTransfer->setResourceShare($resourceShareResponseTransfer->getResourceShare()),
            $resourceShareResponseTransfer->getMessages()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareRequestTransfer $resourceShareRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    protected function executeBeforeZedClientActivatros(ResourceShareRequestTransfer $resourceShareRequestTransfer): ResourceShareResponseTransfer
    {
        $resourceShareResponseTransfer = $this->executeResourceShareActivatorStrategyPlugins(
            $this->beforeZedResourceShareActivatorStrategyPlugins,
            $resourceShareRequestTransfer
        );

        return $resourceShareResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareRequestTransfer $resourceShareRequestTransfer
     * @param \ArrayObject $messageTransfers
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    protected function executeZedCallResourceShareActivators(ResourceShareRequestTransfer $resourceShareRequestTransfer, ArrayObject $messageTransfers): ResourceShareResponseTransfer
    {
        $resourceShareResponseTransfer = $this->zedResourceShareStub->activateResourceShare($resourceShareRequestTransfer);
        $resourceShareResponseTransfer->setMessages(
            $this->mergeResponseMessages(
                $messageTransfers,
                $resourceShareResponseTransfer->getMessages()
            )
        );

        return $resourceShareResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareRequestTransfer $resourceShareRequestTransfer
     * @param \ArrayObject $messageTransfers
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    protected function executeAfterZedClientActivators(ResourceShareRequestTransfer $resourceShareRequestTransfer, ArrayObject $messageTransfers): ResourceShareResponseTransfer
    {
        $resourceShareResponseTransfer = $this->executeResourceShareActivatorStrategyPlugins(
            $this->afterZedResourceShareActivatorStrategyPlugins,
            $resourceShareRequestTransfer
        );

        $resourceShareResponseTransfer->setMessages(
            $this->mergeResponseMessages(
                $messageTransfers,
                $resourceShareResponseTransfer->getMessages()
            )
        );

        return $resourceShareResponseTransfer;
    }

    /**
     * @param \Spryker\Client\ResourceShareExtension\Dependency\Plugin\ResourceShareClientActivatorStrategyPluginInterface[] $resourceShareActivatorStrategyPlugins
     * @param \Generated\Shared\Transfer\ResourceShareRequestTransfer $resourceShareRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    protected function executeResourceShareActivatorStrategyPlugins(
        array $resourceShareActivatorStrategyPlugins,
        ResourceShareRequestTransfer $resourceShareRequestTransfer
    ): ResourceShareResponseTransfer {
        $resourceShareResponseTransfer = (new ResourceShareResponseTransfer())
            ->setResourceShare($resourceShareRequestTransfer->getResourceShare())
            ->setIsSuccessful(true);

        foreach ($resourceShareActivatorStrategyPlugins as $resourceShareActivatorStrategyPlugin) {
            if (!$resourceShareActivatorStrategyPlugin->isApplicable($resourceShareRequestTransfer)) {
                continue;
            }

            $resourceShareResponseTransfer = $resourceShareActivatorStrategyPlugin->execute($resourceShareRequestTransfer);
            break;
        }

        return $resourceShareResponseTransfer;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\MessageTransfer[] $targetMessageTransfers
     * @param \ArrayObject|\Generated\Shared\Transfer\MessageTransfer[] $messageTransfers
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\MessageTransfer[]
     */
    protected function mergeResponseMessages(
        ArrayObject $targetMessageTransfers,
        ArrayObject $messageTransfers
    ): ArrayObject {
        foreach ($messageTransfers as $messageTransfer) {
            $targetMessageTransfers->append($messageTransfer);
        }

        return $targetMessageTransfers;
    }
}
