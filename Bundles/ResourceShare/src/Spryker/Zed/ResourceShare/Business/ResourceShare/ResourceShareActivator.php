<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ResourceShare\Business\ResourceShare;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ResourceShareRequestTransfer;
use Generated\Shared\Transfer\ResourceShareResponseTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\ResourceShare\Persistence\ResourceShareRepositoryInterface;
use Spryker\Zed\ResourceShareExtension\Dependency\Plugin\ResourceShareActivatorStrategyPluginInterface;

class ResourceShareActivator implements ResourceShareActivatorInterface
{
    use TransactionTrait;

    protected const GLOSSARY_KEY_RESOURCE_IS_NOT_FOUND_BY_PROVIDED_UUID = 'resource_share.activator.error.resource_is_not_found_by_provided_uuid';
    protected const GLOSSARY_KEY_STRATEGY_EXPECTS_LOGGED_IN_CUSTOMER = 'resource_share.activator.error.strategy_expects_logged_in_customer';

    /**
     * @var \Spryker\Zed\ResourceShare\Persistence\ResourceShareRepositoryInterface
     */
    protected $resourceShareRepository;

    /**
     * @var \Spryker\Zed\ResourceShareExtension\Dependency\Plugin\ResourceShareActivatorStrategyPluginInterface[]
     */
    protected $resourceShareActivatorStrategyPlugins;

    /**
     * @param \Spryker\Zed\ResourceShare\Persistence\ResourceShareRepositoryInterface $resourceShareRepository
     * @param array $resourceShareActivatorStrategyPlugins
     */
    public function __construct(
        ResourceShareRepositoryInterface $resourceShareRepository,
        array $resourceShareActivatorStrategyPlugins
    ) {
        $this->resourceShareRepository = $resourceShareRepository;
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
        $resourceShareResponseTransfer = new ResourceShareResponseTransfer();

        if (!$resourceShareRequestTransfer->getUuid()) {
            return $resourceShareResponseTransfer->addErrorMessage(
                (new MessageTransfer())->setValue(static::GLOSSARY_KEY_RESOURCE_IS_NOT_FOUND_BY_PROVIDED_UUID)
            );
        }

        $resourceShareTransfer = $this->resourceShareRepository->findResourceShareByUuid(
            $resourceShareRequestTransfer->getUuid()
        );

        if (!$resourceShareTransfer) {
            return $resourceShareResponseTransfer->addErrorMessage(
                (new MessageTransfer())->setValue(static::GLOSSARY_KEY_RESOURCE_IS_NOT_FOUND_BY_PROVIDED_UUID)
            );
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
        $isCustomerLoggedIn = $this->isCustomerLoggedIn($resourceShareRequestTransfer);

        foreach ($this->resourceShareActivatorStrategyPlugins as $resourceShareActivatorStrategyPlugin) {
            if (!$resourceShareActivatorStrategyPlugin->isApplicable($resourceShareTransfer)) {
                continue;
            }

            if (!$isCustomerLoggedIn && $resourceShareActivatorStrategyPlugin->isLoginRequired()) {
                return $resourceShareResponseTransfer->setIsSuccessful(false)
                    ->setIsLoginRequired(true)
                    ->addErrorMessage(
                        (new MessageTransfer())->setValue(static::GLOSSARY_KEY_STRATEGY_EXPECTS_LOGGED_IN_CUSTOMER)
                    );
            }

            $this->executeActivatorStrategyPlugin($resourceShareActivatorStrategyPlugin, $resourceShareRequestTransfer);
            break;
        }

        return $resourceShareResponseTransfer->setIsSuccessful(true)
            ->setResourceShare($resourceShareTransfer);
    }

    /**
     * @param \Spryker\Zed\ResourceShareExtension\Dependency\Plugin\ResourceShareActivatorStrategyPluginInterface $resourceShareActivatorStrategyPlugin
     * @param \Generated\Shared\Transfer\ResourceShareRequestTransfer $resourceShareRequestTransfer
     *
     * @return void
     */
    protected function executeActivatorStrategyPlugin(
        ResourceShareActivatorStrategyPluginInterface $resourceShareActivatorStrategyPlugin,
        ResourceShareRequestTransfer $resourceShareRequestTransfer
    ): void {
        $resourceShareTransfer = $resourceShareRequestTransfer->getResourceShare();

        if ($resourceShareRequestTransfer->getUseDbTransaction()) {
            $this->getTransactionHandler()->handleTransaction(function () use ($resourceShareActivatorStrategyPlugin, $resourceShareTransfer): void {
                $resourceShareActivatorStrategyPlugin->execute($resourceShareTransfer);
            });

            return;
        }

        $resourceShareActivatorStrategyPlugin->execute($resourceShareTransfer);
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
