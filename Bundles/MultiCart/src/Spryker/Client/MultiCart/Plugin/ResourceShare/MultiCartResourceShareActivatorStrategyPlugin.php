<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MultiCart\Plugin\ResourceShare;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ResourceShareRequestTransfer;
use Generated\Shared\Transfer\ResourceShareResponseTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\ResourceShareExtension\Dependency\Plugin\ResourceShareActivatorStrategyPluginInterface;

/**
 * @method \Spryker\Client\MultiCart\MultiCartClientInterface getClient()
 */
class MultiCartResourceShareActivatorStrategyPlugin extends AbstractPlugin implements ResourceShareActivatorStrategyPluginInterface
{
    protected const GLOSSARY_KEY_RESOURCE_IS_NOT_AVAILABLE = 'persistent_cart_share.error.resource_is_not_available';

    /**
     * @uses \Spryker\Shared\SharedCart\SharedCartConfig::QUOTE_RESOURCE_TYPE
     */
    protected const QUOTE_RESOURCE_TYPE = 'quote';

    /**
     * @uses \Spryker\Shared\SharedCart\SharedCartConfig::PERMISSION_GROUP_READ_ONLY
     */
    protected const PERMISSION_GROUP_READ_ONLY = 'READ_ONLY';

    /**
     * @uses \Spryker\Shared\SharedCart\SharedCartConfig::PERMISSION_GROUP_FULL_ACCESS
     */
    protected const PERMISSION_GROUP_FULL_ACCESS = 'FULL_ACCESS';

    /**
     * {@inheritdoc}
     * - Switches default cart, based on idQuote from resource share data.
     *
     * @param \Generated\Shared\Transfer\ResourceShareRequestTransfer $resourceShareRequestTransfer
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    public function execute(ResourceShareRequestTransfer $resourceShareRequestTransfer): ResourceShareResponseTransfer
    {
        $resourceShareDataTransfer = $resourceShareRequestTransfer->getResourceShare()
            ->getResourceShareData();

        $resourceShareDataTransfer->requireIdQuote();
        $quoteTransfer = $this->getClient()->findQuoteById($resourceShareDataTransfer->getIdQuote());
        if (!$quoteTransfer) {
            return (new ResourceShareResponseTransfer())
                ->setIsSuccessful(false)
                ->addMessage(
                    (new MessageTransfer())->setValue(static::GLOSSARY_KEY_RESOURCE_IS_NOT_AVAILABLE)
                );
        }

        $quoteResponseTransfer = $this->getClient()->setDefaultQuote($quoteTransfer);
        if (!$quoteResponseTransfer->getIsSuccessful()) {
            $resourceShareResponseTransfer = (new ResourceShareResponseTransfer())
                ->setIsSuccessful(false);

            foreach ($quoteResponseTransfer->getErrors() as $quoteErrorTransfer) {
                $resourceShareResponseTransfer->addMessage(
                    (new MessageTransfer())->setValue($quoteErrorTransfer->getMessage())
                );
            }

            return $resourceShareResponseTransfer;
        }

        return (new ResourceShareResponseTransfer())
            ->setIsSuccessful(true)
            ->setResourceShare($resourceShareRequestTransfer->getResourceShare());
    }

    /**
     * {@inheritdoc}
     * - Returns 'true', as activator strategy expects the customer to be logged in.
     *
     * @api
     *
     * @return bool
     */
    public function isLoginRequired(): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     * - Checks if strategy plugin is applicable, based on resource data and provided customer.
     *
     * @param \Generated\Shared\Transfer\ResourceShareRequestTransfer $resourceShareRequestTransfer
     *
     * @api
     *
     * @return bool
     */
    public function isApplicable(ResourceShareRequestTransfer $resourceShareRequestTransfer): bool
    {
        $customerTransfer = $resourceShareRequestTransfer->getCustomer();
        if (!$customerTransfer->getCompanyUserTransfer()) {
            return false;
        }

        $resourceShareTransfer = $resourceShareRequestTransfer->getResourceShare();
        $resourceShareTransfer->requireResourceType();
        if ($resourceShareTransfer->getResourceType() !== static::QUOTE_RESOURCE_TYPE) {
            return false;
        }

        $resourceShareTransfer->requireResourceShareData();
        $resourceShareDataTransfer = $resourceShareTransfer->getResourceShareData();

        return in_array($resourceShareDataTransfer->getShareOption(), [static::PERMISSION_GROUP_READ_ONLY, static::PERMISSION_GROUP_FULL_ACCESS], true);
    }
}
