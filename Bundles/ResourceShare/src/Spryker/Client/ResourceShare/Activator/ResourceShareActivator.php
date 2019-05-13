<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ResourceShare\Activator;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ResourceShareRequestTransfer;
use Generated\Shared\Transfer\ResourceShareResponseTransfer;
use Spryker\Client\ResourceShare\Zed\ResourceShareStubInterface;

class ResourceShareActivator implements ResourceShareActivatorInterface
{
    protected const GLOSSARY_KEY_STRATEGY_EXPECTS_LOGGED_IN_CUSTOMER = 'resource_share.activator.error.strategy_expects_logged_in_customer';

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
        $resourceShareResponseTransfer = $this->zedResourceShareStub->activateResourceShare($resourceShareRequestTransfer);
        if (!$resourceShareResponseTransfer->getIsSuccessful()) {
            return $resourceShareResponseTransfer;
        }

        return $this->executeResourceShareActivatorStrategyPlugins($resourceShareRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareRequestTransfer $resourceShareRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    protected function executeResourceShareActivatorStrategyPlugins(
        ResourceShareRequestTransfer $resourceShareRequestTransfer
    ): ResourceShareResponseTransfer {
        $resourceShareResponseTransfer = new ResourceShareResponseTransfer();
        $resourceShareTransfer = $resourceShareRequestTransfer->getResourceShare();

        foreach ($this->resourceShareActivatorStrategyPlugins as $resourceShareActivatorStrategyPlugin) {
            if (!$resourceShareActivatorStrategyPlugin->isApplicable($resourceShareTransfer)) {
                continue;
            }

            if (!$resourceShareRequestTransfer->getCustomer() && $resourceShareActivatorStrategyPlugin->isLoginRequired()) {
                return $resourceShareResponseTransfer->setIsSuccessful(false)
                    ->setIsLoginRequired(true)
                    ->addMessage(
                        (new MessageTransfer())->setValue(static::GLOSSARY_KEY_STRATEGY_EXPECTS_LOGGED_IN_CUSTOMER)
                    );
            }

            $strategyResourceShareResponseTransfer = $resourceShareActivatorStrategyPlugin->execute($resourceShareRequestTransfer);
            if (!$strategyResourceShareResponseTransfer->getIsSuccessful()) {
                return $strategyResourceShareResponseTransfer;
            }

            break;
        }

        return $resourceShareResponseTransfer->setIsSuccessful(true)
            ->setResourceShare($resourceShareTransfer);
    }
}
