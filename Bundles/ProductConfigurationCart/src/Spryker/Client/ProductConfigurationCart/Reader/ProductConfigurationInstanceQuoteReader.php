<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationCart\Reader;

use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\ProductConfigurationCart\Dependency\Client\ProductConfigurationCartToCartClientInterface;

class ProductConfigurationInstanceQuoteReader implements ProductConfigurationInstanceQuoteReaderInterface
{
    /**
     * @var \Spryker\Client\ProductConfigurationCart\Dependency\Client\ProductConfigurationCartToCartClientInterface
     */
    protected $cartClient;

    /**
     * @param \Spryker\Client\ProductConfigurationCart\Dependency\Client\ProductConfigurationCartToCartClientInterface $cartClient
     */
    public function __construct(ProductConfigurationCartToCartClientInterface $cartClient)
    {
        $this->cartClient = $cartClient;
    }

    /**
     * @param string $groupKey
     * @param string $sku
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer|null
     */
    public function findProductConfigurationInstanceInQuote(
        string $groupKey,
        string $sku,
        QuoteTransfer $quoteTransfer
    ): ?ProductConfigurationInstanceTransfer {
        $itemTransfer = $this->cartClient->findQuoteItem(
            $quoteTransfer,
            $sku,
            $groupKey
        );

        if (!$itemTransfer || !$itemTransfer->getProductConfigurationInstance()) {
            return null;
        }

        return (new ProductConfigurationInstanceTransfer())->fromArray(
            $itemTransfer->getProductConfigurationInstanceOrFail()->toArray()
        );
    }
}
