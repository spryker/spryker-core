<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Quote\Communication\Plugin\ResourceShare;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ResourceShareRequestTransfer;
use Generated\Shared\Transfer\ResourceShareResponseTransfer;
use Spryker\Shared\Quote\QuoteConfig as SharedQuoteConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ResourceShareExtension\Dependency\Plugin\ResourceShareActivatorStrategyPluginInterface;

/**
 * @method \Spryker\Zed\Quote\Business\QuoteFacadeInterface getFacade()
 * @method \Spryker\Zed\Quote\QuoteConfig getConfig()
 */
class QuoteIsLockedActivatorStrategyPlugin extends AbstractPlugin implements ResourceShareActivatorStrategyPluginInterface
{
    /**
     * @uses \Spryker\Zed\PersistentCartShare\Business\Quote\QuoteReader::GLOSSARY_KEY_QUOTE_IS_NOT_AVAILABLE
     */
    protected const GLOSSARY_KEY_QUOTE_IS_NOT_AVAILABLE = 'persistent_cart_share.error.quote_is_not_available';

    /**
     * {@inheritdoc}
     * - Returns 'isSuccessful=false' with error message.
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
            ->addMessage(
                (new MessageTransfer())->setValue(static::GLOSSARY_KEY_QUOTE_IS_NOT_AVAILABLE)
            );
    }

    /**
     * {@inheritdoc}
     * - Returns false, since it doesn't matter whether customer is logged-in or not to apply this activator strategy.
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
     * - Returns 'true', when resource type is Quote and the Quote is locked.
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
        $resourceShareTransfer = $resourceShareRequestTransfer->getResourceShare();
        $resourceShareTransfer->requireResourceType();
        if ($resourceShareTransfer->getResourceType() !== SharedQuoteConfig::RESOURCE_TYPE_QUOTE) {
            return false;
        }

        $resourceShareTransfer->requireResourceShareData();
        $resourceShareDataTransfer = $resourceShareTransfer->getResourceShareData();

        $quoteResponseTransfer = $this->getFacade()->findQuoteById(
            $resourceShareDataTransfer->getIdQuote()
        );

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return false;
        }

        $quoteTransfer = $quoteResponseTransfer
            ->requireQuoteTransfer()
            ->getQuoteTransfer();

        return $this->getFacade()->isQuoteLocked($quoteTransfer);
    }
}
