<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundleCart\Writer;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\QuoteErrorTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Spryker\Client\ConfigurableBundleCart\Calculator\ItemsQuantityCalculatorInterface;
use Spryker\Client\ConfigurableBundleCart\Dependency\Client\ConfigurableBundleCartToCartClientInterface;
use Spryker\Client\ConfigurableBundleCart\Reader\QuoteReaderInterface;

class CartWriter implements CartWriterInterface
{
    protected const GLOSSARY_KEY_CONFIGURED_BUNDLE_NOT_FOUND = 'configured_bundle_cart.error.configured_bundle_not_found';
    protected const GLOSSARY_KEY_CONFIGURED_BUNDLE_CANNOT_BE_REMOVED = 'configured_bundle_cart.error.configured_bundle_cannot_be_removed';

    /**
     * @var \Spryker\Client\ConfigurableBundleCart\Dependency\Client\ConfigurableBundleCartToCartClientInterface
     */
    protected $cartClient;

    /**
     * @var \Spryker\Client\ConfigurableBundleCart\Reader\QuoteReaderInterface
     */
    protected $quoteReader;

    /**
     * @var \Spryker\Client\ConfigurableBundleCart\Calculator\ItemsQuantityCalculatorInterface
     */
    protected $itemsQuantityCalculator;

    /**
     * @param \Spryker\Client\ConfigurableBundleCart\Dependency\Client\ConfigurableBundleCartToCartClientInterface $cartClient
     * @param \Spryker\Client\ConfigurableBundleCart\Reader\QuoteReaderInterface $quoteReader
     * @param \Spryker\Client\ConfigurableBundleCart\Calculator\ItemsQuantityCalculatorInterface $itemsQuantityCalculator
     */
    public function __construct(
        ConfigurableBundleCartToCartClientInterface $cartClient,
        QuoteReaderInterface $quoteReader,
        ItemsQuantityCalculatorInterface $itemsQuantityCalculator
    ) {
        $this->cartClient = $cartClient;
        $this->quoteReader = $quoteReader;
        $this->itemsQuantityCalculator = $itemsQuantityCalculator;
    }

    /**
     * @param string $configuredBundleGroupKey
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function removeConfiguredBundle(string $configuredBundleGroupKey): QuoteResponseTransfer
    {
        $itemTransfers = $this->quoteReader
            ->getItemsByConfiguredBundleGroupKey($configuredBundleGroupKey, $this->cartClient->getQuote());

        if (!$itemTransfers->count()) {
            return $this->getErrorResponse(static::GLOSSARY_KEY_CONFIGURED_BUNDLE_NOT_FOUND);
        }

        $quoteTransfer = $this->cartClient->removeItems($itemTransfers);

        if ($this->quoteReader->getItemsByConfiguredBundleGroupKey($configuredBundleGroupKey, $quoteTransfer)->count()) {
            return $this->getErrorResponse(static::GLOSSARY_KEY_CONFIGURED_BUNDLE_CANNOT_BE_REMOVED);
        }

        return (new QuoteResponseTransfer())
            ->setIsSuccessful(true)
            ->setQuoteTransfer($quoteTransfer);
    }

    /**
     * @param string $configuredBundleGroupKey
     * @param int $configuredBundleQuantity
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function updateConfiguredBundleQuantity(string $configuredBundleGroupKey, int $configuredBundleQuantity): QuoteResponseTransfer
    {
        $quoteTransfer = $this->cartClient->getQuote();
        $itemTransfers = $this->quoteReader->getItemsByConfiguredBundleGroupKey($configuredBundleGroupKey, $quoteTransfer);

        $itemTransfers = $this->itemsQuantityCalculator->updateItemsQuantity($itemTransfers, $configuredBundleQuantity);

        $cartChangeTransfer = (new CartChangeTransfer())
            ->setItems($itemTransfers)
            ->setQuote($quoteTransfer);

//        $quoteResponseTransfer = $this->cartClient->updateQuantity($cartChangeTransfer);

        return new QuoteResponseTransfer();
    }

    /**
     * @param string $message
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    protected function getErrorResponse(string $message): QuoteResponseTransfer
    {
        return (new QuoteResponseTransfer())
            ->setIsSuccessful(false)
            ->addError((new QuoteErrorTransfer())->setMessage($message));
    }
}
