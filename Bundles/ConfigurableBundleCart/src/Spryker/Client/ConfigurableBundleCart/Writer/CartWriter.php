<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundleCart\Writer;

use ArrayObject;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Spryker\Client\ConfigurableBundleCart\Dependency\Client\ConfigurableBundleCartToCartClientInterface;
use Spryker\Client\ConfigurableBundleCart\Reader\QuoteReaderInterface;

class CartWriter implements CartWriterInterface
{
    /**
     * @var \Spryker\Client\ConfigurableBundleCart\Dependency\Client\ConfigurableBundleCartToCartClientInterface
     */
    protected $cartClient;

    /**
     * @var \Spryker\Client\ConfigurableBundleCart\Reader\QuoteReaderInterface
     */
    protected $quoteReader;

    /**
     * @param \Spryker\Client\ConfigurableBundleCart\Dependency\Client\ConfigurableBundleCartToCartClientInterface $cartClient
     * @param \Spryker\Client\ConfigurableBundleCart\Reader\QuoteReaderInterface $quoteReader
     */
    public function __construct(
        ConfigurableBundleCartToCartClientInterface $cartClient,
        QuoteReaderInterface $quoteReader
    ) {
        $this->cartClient = $cartClient;
        $this->quoteReader = $quoteReader;
    }

    /**
     * @param string $configuredBundleGroupKey
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function removeConfiguredBundle(string $configuredBundleGroupKey): QuoteResponseTransfer
    {
        $itemTransfers = $this->quoteReader->getItemsByConfiguredBundleGroupKey($configuredBundleGroupKey);

        if (!$itemTransfers) {
            // TODO: create QuoteResponseTransfer with error message.

            return (new QuoteResponseTransfer())
                ->setIsSuccessful(false);
        }

        $quoteTransfer = $this->cartClient->removeItems(new ArrayObject($itemTransfers));

        // TODO: create QuoteResponseTransfer with error messages.

        return (new QuoteResponseTransfer())
            ->setIsSuccessful(true)
            ->setQuoteTransfer($quoteTransfer);
    }

    /**
     * @param string $configuredBundleGroupKey
     * @param int $quantity
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function updateConfiguredBundleQuantity(string $configuredBundleGroupKey, int $quantity): QuoteResponseTransfer
    {
        // TODO: Implement updateConfiguredBundleQuantity() method.

        return new QuoteResponseTransfer();
    }
}
