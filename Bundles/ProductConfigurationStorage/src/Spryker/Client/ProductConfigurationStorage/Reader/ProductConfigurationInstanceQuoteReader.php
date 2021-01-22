<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationStorage\Reader;

use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\ProductConfigurationStorage\Dependency\Client\ProductConfigurationStorageToCartClientInterface;

class ProductConfigurationInstanceQuoteReader implements ProductConfigurationInstanceQuoteReaderInterface
{
    /**
     * @var \Spryker\Client\ProductConfigurationStorage\Dependency\Client\ProductConfigurationStorageToCartClientInterface
     */
    protected $cartClient;

    /**
     * @param \Spryker\Client\ProductConfigurationStorage\Dependency\Client\ProductConfigurationStorageToCartClientInterface $cartClient
     */
    public function __construct(ProductConfigurationStorageToCartClientInterface $cartClient)
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

        if (!$itemTransfer) {
            return null;
        }

        return (new ProductConfigurationInstanceTransfer())
            ->fromArray($itemTransfer->getProductConfigurationInstance()->toArray());
    }
}
