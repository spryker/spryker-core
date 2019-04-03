<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ResourceShare\Business\ResourceShare;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ResourceShareResponseTransfer;
use Generated\Shared\Transfer\ResourceShareTransfer;

class ResourceShareActivator implements ResourceShareActivatorInterface
{
    protected const ERROR_MESSAGE_STRATEGY_EXPECTS_LOGGED_IN_CUSTOMER = 'Strategy expects logged in customer.';

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
     * @param string $uuid
     * @param \Generated\Shared\Transfer\CustomerTransfer|null $customerTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    public function activateResourceShare(
        string $uuid,
        ?CustomerTransfer $customerTransfer
    ): ResourceShareResponseTransfer {
        $resourceShareReaderResponseTransfer = $this->resourceShareReader->getResourceShareByUuid($uuid);
        if (!$resourceShareReaderResponseTransfer->getIsSuccessful()) {
            return $resourceShareReaderResponseTransfer;
        }

        $resourceShareTransfer = $resourceShareReaderResponseTransfer->getResourceShare();

        return $this->executeResourceShareActivatorStrategyPlugins($resourceShareTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareTransfer $resourceShareTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    protected function executeResourceShareActivatorStrategyPlugins(ResourceShareTransfer $resourceShareTransfer): ResourceShareResponseTransfer
    {
        $resourceShareResponseTransfer = new ResourceShareResponseTransfer();

        foreach ($this->resourceShareActivatorStrategyPlugins as $resourceShareActivatorStrategyPlugin) {
            if ($resourceShareActivatorStrategyPlugin->isLoginRequired()) {
                return $resourceShareResponseTransfer->setIsSuccessful(false)
                    ->addErrorMessage(
                        (new MessageTransfer())->setValue(static::ERROR_MESSAGE_STRATEGY_EXPECTS_LOGGED_IN_CUSTOMER)
                    );
            }

            $resourceShareActivatorStrategyPlugin->execute($resourceShareTransfer);
        }

        return $resourceShareResponseTransfer->setIsSuccessful(true)
            ->setResourceShare($resourceShareTransfer);
    }
}
