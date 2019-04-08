<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ResourceShare\Business\ResourceShare;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ResourceShareCriteriaTransfer;
use Generated\Shared\Transfer\ResourceShareRequestTransfer;
use Generated\Shared\Transfer\ResourceShareResponseTransfer;
use Generated\Shared\Transfer\ResourceShareTransfer;

class ResourceShareActivator implements ResourceShareActivatorInterface
{
    protected const GLOSSARY_KEY_STRATEGY_EXPECTS_LOGGED_IN_CUSTOMER = 'resource_share.activator.error.strategy_expects_logged_in_customer';

    /**
     * @var \Spryker\Zed\ResourceShare\Business\ResourceShare\ResourceShareReaderInterface
     */
    protected $resourceShareReader;

    /**
     * @var \Spryker\Zed\ResourceShareExtension\Dependency\Plugin\ResourceShareActivatorStrategyPluginInterface[]
     */
    protected $resourceShareActivatorStrategyPlugins;

    /**
     * @param \Spryker\Zed\ResourceShare\Business\ResourceShare\ResourceShareReaderInterface $resourceShareReader
     * @param \Spryker\Zed\ResourceShareExtension\Dependency\Plugin\ResourceShareActivatorStrategyPluginInterface[] $resourceShareActivatorStrategyPlugins
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
        $resourceShareRequestTransfer->requireUuid();

        $resourceShareResponseTransfer = $this->resourceShareReader->getResourceShare(
            (new ResourceShareCriteriaTransfer())->setUuid($resourceShareRequestTransfer->getUuid())
        );

        if (!$resourceShareResponseTransfer->getIsSuccessful()) {
            return $resourceShareResponseTransfer;
        }

        $resourceShareResponseTransfer->requireResourceShare();
        $resourceShareTransfer = $resourceShareResponseTransfer->getResourceShare();

        return $this->executeResourceShareActivatorStrategyPlugins(
            $resourceShareTransfer,
            $resourceShareRequestTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareTransfer $resourceShareTransfer
     * @param \Generated\Shared\Transfer\ResourceShareRequestTransfer $resourceShareRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    protected function executeResourceShareActivatorStrategyPlugins(
        ResourceShareTransfer $resourceShareTransfer,
        ResourceShareRequestTransfer $resourceShareRequestTransfer
    ): ResourceShareResponseTransfer {
        $resourceShareResponseTransfer = (new ResourceShareResponseTransfer())
            ->setIsLoginRequired(false);

        $isCustomerLoggedIn = $this->isCustomerLoggedIn($resourceShareRequestTransfer);
        foreach ($this->resourceShareActivatorStrategyPlugins as $resourceShareActivatorStrategyPlugin) {
            if (!$isCustomerLoggedIn && $resourceShareActivatorStrategyPlugin->isLoginRequired()) {
                return $resourceShareResponseTransfer->setIsSuccessful(false)
                    ->setIsLoginRequired(true)
                    ->addErrorMessage(
                        (new MessageTransfer())->setValue(static::GLOSSARY_KEY_STRATEGY_EXPECTS_LOGGED_IN_CUSTOMER)
                    );
            }

            $resourceShareActivatorStrategyPlugin->execute($resourceShareTransfer);
        }

        return $resourceShareResponseTransfer->setIsSuccessful(true)
            ->setResourceShare($resourceShareTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareRequestTransfer $resourceShareRequestTransfer
     *
     * @return bool
     */
    protected function isCustomerLoggedIn(ResourceShareRequestTransfer $resourceShareRequestTransfer): bool
    {
        $customerTransfer = $resourceShareRequestTransfer->getCustomer();

        return $customerTransfer && !$customerTransfer->getIsGuest();
    }
}
