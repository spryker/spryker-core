<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundleCart\Reader;

use Spryker\Client\ConfigurableBundleCart\Dependency\Client\ConfigurableBundleCartToCartClientInterface;

class QuoteReader implements QuoteReaderInterface
{
    /**
     * @var \Spryker\Client\ConfigurableBundleCart\Dependency\Client\ConfigurableBundleCartToCartClientInterface
     */
    protected $cartClient;

    /**
     * @param \Spryker\Client\ConfigurableBundleCart\Dependency\Client\ConfigurableBundleCartToCartClientInterface $cartClient
     */
    public function __construct(ConfigurableBundleCartToCartClientInterface $cartClient)
    {
        $this->cartClient = $cartClient;
    }

    /**
     * @param string $groupKey
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function getItemsByConfiguredBundleGroupKey(string $groupKey): array
    {
        $itemTransfers = [];
        $quoteTransfer = $this->cartClient->getQuote();

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getConfiguredBundle() && $itemTransfer->getConfiguredBundle()->getGroupKey() === $groupKey) {
                $itemTransfers[] = $itemTransfer;
            }
        }

        return $itemTransfers;
    }
}
