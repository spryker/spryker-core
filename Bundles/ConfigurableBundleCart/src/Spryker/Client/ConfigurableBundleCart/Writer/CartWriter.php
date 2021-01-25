<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundleCart\Writer;

use Generated\Shared\Transfer\QuoteErrorTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\UpdateConfiguredBundleRequestTransfer;
use Spryker\Client\ConfigurableBundleCart\Dependency\Client\ConfigurableBundleCartToCartClientInterface;
use Spryker\Client\ConfigurableBundleCart\Reader\QuoteItemReaderInterface;
use Spryker\Client\ConfigurableBundleCart\Updater\QuoteItemUpdaterInterface;

class CartWriter implements CartWriterInterface
{
    protected const GLOSSARY_KEY_CONFIGURED_BUNDLE_NOT_FOUND = 'configured_bundle_cart.error.configured_bundle_not_found';
    protected const GLOSSARY_KEY_CONFIGURED_BUNDLE_CANNOT_BE_REMOVED = 'configured_bundle_cart.error.configured_bundle_cannot_be_removed';
    protected const GLOSSARY_KEY_CONFIGURED_BUNDLE_CANNOT_BE_UPDATED = 'configured_bundle_cart.error.configured_bundle_cannot_be_updated';

    /**
     * @var \Spryker\Client\ConfigurableBundleCart\Dependency\Client\ConfigurableBundleCartToCartClientInterface
     */
    protected $cartClient;

    /**
     * @var \Spryker\Client\ConfigurableBundleCart\Reader\QuoteItemReaderInterface
     */
    protected $quoteItemReader;

    /**
     * @var \Spryker\Client\ConfigurableBundleCart\Updater\QuoteItemUpdaterInterface
     */
    protected $quoteItemUpdater;

    /**
     * @param \Spryker\Client\ConfigurableBundleCart\Dependency\Client\ConfigurableBundleCartToCartClientInterface $cartClient
     * @param \Spryker\Client\ConfigurableBundleCart\Reader\QuoteItemReaderInterface $quoteItemReader
     * @param \Spryker\Client\ConfigurableBundleCart\Updater\QuoteItemUpdaterInterface $quoteItemUpdater
     */
    public function __construct(
        ConfigurableBundleCartToCartClientInterface $cartClient,
        QuoteItemReaderInterface $quoteItemReader,
        QuoteItemUpdaterInterface $quoteItemUpdater
    ) {
        $this->cartClient = $cartClient;
        $this->quoteItemReader = $quoteItemReader;
        $this->quoteItemUpdater = $quoteItemUpdater;
    }

    /**
     * @param \Generated\Shared\Transfer\UpdateConfiguredBundleRequestTransfer $updateConfiguredBundleRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function removeConfiguredBundle(UpdateConfiguredBundleRequestTransfer $updateConfiguredBundleRequestTransfer): QuoteResponseTransfer
    {
        $cartChangeTransfer = $this->quoteItemReader->getItemsByConfiguredBundleGroupKey($updateConfiguredBundleRequestTransfer);

        if (!$cartChangeTransfer->getItems()->count()) {
            return $this->createErrorResponse(static::GLOSSARY_KEY_CONFIGURED_BUNDLE_NOT_FOUND);
        }

        $quoteResponseTransfer = $this->cartClient->removeFromCart($cartChangeTransfer);

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $this->createErrorResponse(static::GLOSSARY_KEY_CONFIGURED_BUNDLE_CANNOT_BE_REMOVED);
        }

        return $quoteResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\UpdateConfiguredBundleRequestTransfer $updateConfiguredBundleRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function updateConfiguredBundleQuantity(UpdateConfiguredBundleRequestTransfer $updateConfiguredBundleRequestTransfer): QuoteResponseTransfer
    {
        $cartChangeTransfer = $this->quoteItemUpdater->changeQuantity($updateConfiguredBundleRequestTransfer);

        if (!$cartChangeTransfer->getItems()->count()) {
            return $this->createErrorResponse(static::GLOSSARY_KEY_CONFIGURED_BUNDLE_NOT_FOUND);
        }

        $quoteResponseTransfer = $this->cartClient->updateQuantity($cartChangeTransfer);

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $this->createErrorResponse(static::GLOSSARY_KEY_CONFIGURED_BUNDLE_CANNOT_BE_UPDATED);
        }

        return $quoteResponseTransfer;
    }

    /**
     * @param string $message
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    protected function createErrorResponse(string $message): QuoteResponseTransfer
    {
        $quoteErrorTransfer = (new QuoteErrorTransfer())
            ->setMessage($message);

        return (new QuoteResponseTransfer())
            ->setIsSuccessful(false)
            ->addError($quoteErrorTransfer);
    }
}
