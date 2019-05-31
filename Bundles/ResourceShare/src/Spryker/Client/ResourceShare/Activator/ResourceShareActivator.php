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
     * @var \Spryker\Client\ResourceShareExtension\Dependency\Plugin\ResourceShareActivatorStrategyPluginInterface[]
     */
    protected $beforeZedResourceShareActivatorStrategyPlugins;

    /**
     * @var array|\Spryker\Client\ResourceShareExtension\Dependency\Plugin\ResourceShareActivatorStrategyPluginInterface[]
     */
    protected $afterZedResourceShareActivatorStrategyPlugins;

    /**
     * @param \Spryker\Client\ResourceShare\Zed\ResourceShareStubInterface $zedResourceShareStub
     * @param \Spryker\Client\ResourceShareExtension\Dependency\Plugin\ResourceShareActivatorStrategyPluginInterface[] $beforeZedResourceShareActivatorStrategyPlugins
     * @param \Spryker\Client\ResourceShareExtension\Dependency\Plugin\ResourceShareActivatorStrategyPluginInterface[] $afterZedResourceShareActivatorStrategyPlugins
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
        $clientBeforeZedResourceShareResponseTransfer = $this->executeResourceShareActivatorStrategyPlugins(
            $this->beforeZedResourceShareActivatorStrategyPlugins,
            $resourceShareRequestTransfer
        );

        if (!$clientBeforeZedResourceShareResponseTransfer->getIsSuccessful()) {
            return $clientBeforeZedResourceShareResponseTransfer;
        }

        $zedResourceShareResponseTransfer = $this->zedResourceShareStub->activateResourceShare($resourceShareRequestTransfer);
        if (!$zedResourceShareResponseTransfer->getIsSuccessful()) {
            return $zedResourceShareResponseTransfer;
        }

        $clientAfterZedResourceShareResponseTransfer = $this->executeResourceShareActivatorStrategyPlugins(
            $this->afterZedResourceShareActivatorStrategyPlugins,
            $resourceShareRequestTransfer
        );

        if (!$clientAfterZedResourceShareResponseTransfer->getIsSuccessful()) {
            return $clientAfterZedResourceShareResponseTransfer;
        }

        return (new ResourceShareResponseTransfer())
            ->setIsSuccessful(true)
            ->setIsLoginRequired(false)
            ->setMessages(
                $this->mergeResponseMessages(
                    $clientBeforeZedResourceShareResponseTransfer->getMessages(),
                    $zedResourceShareResponseTransfer->getMessages(),
                    $clientAfterZedResourceShareResponseTransfer->getMessages()
                )
            );
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\MessageTransfer[] $clientBeforeZedResponseMessageTransfers
     * @param \ArrayObject|\Generated\Shared\Transfer\MessageTransfer[] $zedResponseMessageTransfers
     * @param \ArrayObject|\Generated\Shared\Transfer\MessageTransfer[] $clientAfterZedResponseMessageTransfers
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\MessageTransfer[]
     */
    protected function mergeResponseMessages(
        ArrayObject $clientBeforeZedResponseMessageTransfers,
        ArrayObject $zedResponseMessageTransfers,
        ArrayObject $clientAfterZedResponseMessageTransfers
    ): ArrayObject {
        $responseMessageTransfers = new ArrayObject();

        foreach ($clientBeforeZedResponseMessageTransfers as $messageTransfer) {
            $responseMessageTransfers->append($messageTransfer);
        }

        foreach ($zedResponseMessageTransfers as $messageTransfer) {
            $responseMessageTransfers->append($messageTransfer);
        }

        foreach ($clientAfterZedResponseMessageTransfers as $messageTransfer) {
            $responseMessageTransfers->append($messageTransfer);
        }

        return $responseMessageTransfers;
    }

    /**
     * @param \Spryker\Client\ResourceShareExtension\Dependency\Plugin\ResourceShareActivatorStrategyPluginInterface[] $resourceShareActivatorStrategyPlugins
     * @param \Generated\Shared\Transfer\ResourceShareRequestTransfer $resourceShareRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    protected function executeResourceShareActivatorStrategyPlugins(
        array $resourceShareActivatorStrategyPlugins,
        ResourceShareRequestTransfer $resourceShareRequestTransfer
    ): ResourceShareResponseTransfer {
        $resourceShareResponseTransfer = (new ResourceShareResponseTransfer())
            ->setIsSuccessful(true);

        foreach ($resourceShareActivatorStrategyPlugins as $resourceShareActivatorStrategyPlugin) {
            if (!$resourceShareActivatorStrategyPlugin->isApplicable($resourceShareRequestTransfer)) {
                continue;
            }

            if ($resourceShareActivatorStrategyPlugin->isLoginRequired($resourceShareRequestTransfer->getCustomer())) {
                return $resourceShareResponseTransfer
                    ->setIsLoginRequired(true)
                    ->setIsSuccessful(false);
            }

            $resourceShareResponseTransfer = $resourceShareActivatorStrategyPlugin->execute($resourceShareRequestTransfer);
            break;
        }

        return $resourceShareResponseTransfer;
    }
}
