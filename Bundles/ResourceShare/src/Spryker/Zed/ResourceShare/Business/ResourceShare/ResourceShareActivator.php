<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ResourceShare\Business\ResourceShare;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ResourceShareRequestTransfer;
use Generated\Shared\Transfer\ResourceShareResponseTransfer;
use Spryker\Zed\ResourceShare\ResourceShareConfig;

class ResourceShareActivator implements ResourceShareActivatorInterface
{
    protected const GLOSSARY_KEY_STRATEGY_EXPECTS_LOGGED_IN_CUSTOMER = 'resource_share.activator.error.strategy_expects_logged_in_customer';

    /**
     * @var \Spryker\Zed\ResourceShare\Business\ResourceShare\ResourceShareReaderInterface
     */
    protected $resourceShareReader;

    /**
     * @var \Spryker\Zed\ResourceShare\Business\ResourceShare\ResourceShareValidatorInterface
     */
    protected $resourceShareValidator;

    /**
     * @var \Spryker\Zed\ResourceShareExtension\Dependency\Plugin\ResourceShareActivatorStrategyPluginInterface[]
     */
    protected $resourceShareActivatorStrategyPlugins;

    /**
     * @var \Spryker\Zed\ResourceShareExtension\Dependency\Plugin\ResourceShareResourceDataExpanderStrategyPluginInterface[]
     */
    protected $resourceShareResourceDataExpanderStrategyPlugins;

    /**
     * @param \Spryker\Zed\ResourceShare\Business\ResourceShare\ResourceShareReaderInterface $resourceShareReader
     * @param \Spryker\Zed\ResourceShare\Business\ResourceShare\ResourceShareValidatorInterface $resourceShareValidator
     * @param \Spryker\Zed\ResourceShareExtension\Dependency\Plugin\ResourceShareActivatorStrategyPluginInterface[] $resourceShareActivatorStrategyPlugins
     * @param \Spryker\Zed\ResourceShareExtension\Dependency\Plugin\ResourceShareResourceDataExpanderStrategyPluginInterface[] $resourceShareResourceDataExpanderStrategyPlugins
     */
    public function __construct(
        ResourceShareReaderInterface $resourceShareReader,
        ResourceShareValidatorInterface $resourceShareValidator,
        array $resourceShareActivatorStrategyPlugins,
        array $resourceShareResourceDataExpanderStrategyPlugins
    ) {
        $this->resourceShareReader = $resourceShareReader;
        $this->resourceShareValidator = $resourceShareValidator;
        $this->resourceShareActivatorStrategyPlugins = $resourceShareActivatorStrategyPlugins;
        $this->resourceShareResourceDataExpanderStrategyPlugins = $resourceShareResourceDataExpanderStrategyPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareRequestTransfer $resourceShareRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    public function activateResourceShare(
        ResourceShareRequestTransfer $resourceShareRequestTransfer
    ): ResourceShareResponseTransfer {
        $resourceShareResponseTransfer = $this->resourceShareReader->getResourceShareByUuid($resourceShareRequestTransfer);
        if (!$resourceShareResponseTransfer->getIsSuccessful()) {
            return $resourceShareResponseTransfer;
        }

        $resourceShareTransfer = $resourceShareResponseTransfer->getResourceShare();
        $resourceShareResponseTransfer = $this->resourceShareValidator->validateResourceShareTransfer($resourceShareTransfer);
        if (!$resourceShareResponseTransfer->getIsSuccessful()) {
            return $resourceShareResponseTransfer;
        }

        $resourceShareRequestTransfer->setResourceShare($resourceShareTransfer);

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
                        (new MessageTransfer())
                            ->setType(ResourceShareConfig::ERROR_MESSAGE_TYPE)
                            ->setValue(static::GLOSSARY_KEY_STRATEGY_EXPECTS_LOGGED_IN_CUSTOMER)
                    );
            }

            $resourceShareActivatorStrategyPlugin->execute($resourceShareTransfer);
            break;
        }

        $resourceShareResponseTransfer->setIsSuccessful(true)
            ->setResourceShare($resourceShareTransfer);

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

        return $resourceShareResponseTransfer;
    }
}
