<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PersistentCartShare\Communication\Plugin\ResourceShare;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ResourceShareRequestTransfer;
use Generated\Shared\Transfer\ResourceShareResponseTransfer;
use Spryker\Shared\PersistentCartShare\PersistentCartShareConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ResourceShareExtension\Dependency\Plugin\ResourceShareActivatorStrategyPluginInterface;

/**
 * @method \Spryker\Zed\PersistentCartShare\Business\PersistentCartShareFacade getFacade()
 * @method \Spryker\Zed\PersistentCartShare\PersistentCartShareConfig getConfig()
 */
class GuestQuoteFallbackActivatorStrategyPlugin extends AbstractPlugin implements ResourceShareActivatorStrategyPluginInterface
{
    protected const GLOSSARY_KEY_STRATEGY_EXPECTS_LOGGED_IN_CUSTOMER = 'resource_share.activator.error.strategy_expects_logged_in_customer';

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return bool
     */
    public function isLoginRequired(): bool
    {
        return false;
    }

    /**
     * {@inheritdoc}
     * - Returns 'true', when customer is a guest and resource type is Quote.
     * - Returns 'false' otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ResourceShareRequestTransfer $resourceShareRequestTransfer
     *
     * @return bool
     */
    public function isApplicable(ResourceShareRequestTransfer $resourceShareRequestTransfer): bool
    {
        $customerTransfer = $resourceShareRequestTransfer->getCustomer();
        if ($customerTransfer) {
            return false;
        }

        $resourceShareTransfer = $resourceShareRequestTransfer->getResourceShare();
        if ($resourceShareTransfer->getResourceType() !== PersistentCartShareConfig::RESOURCE_TYPE_QUOTE) {
            return false;
        }

        $resourceShareTransfer->requireResourceShareData();
        $resourceShareDataTransfer = $resourceShareTransfer->getResourceShareData();

        if (!$resourceShareDataTransfer->getIdQuote()) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     * - Returns ResourceShareResponseTransfer with access denied error message.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ResourceShareRequestTransfer $resourceShareRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    public function execute(ResourceShareRequestTransfer $resourceShareRequestTransfer): ResourceShareResponseTransfer
    {
        return (new ResourceShareResponseTransfer())
            ->setIsSuccessful(false)
            ->setIsLoginRequired(true)
            ->addMessage(
                (new MessageTransfer())
                    ->setType(PersistentCartShareConfig::MESSAGE_TYPE_ERROR)
                    ->setValue(static::GLOSSARY_KEY_STRATEGY_EXPECTS_LOGGED_IN_CUSTOMER)
            );
    }
}
