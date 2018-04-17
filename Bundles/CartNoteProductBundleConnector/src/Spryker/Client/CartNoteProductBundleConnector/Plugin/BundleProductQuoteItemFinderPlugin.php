<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CartNoteProductBundleConnector\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\CartNoteExtension\Dependency\Plugin\QuoteItemFinderPluginInterface;
use Spryker\Client\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Client\CartNoteProductBundleConnector\CartNoteProductBundleConnectorFactory getFactory()
 */
class BundleProductQuoteItemFinderPlugin extends AbstractPlugin implements QuoteItemFinderPluginInterface
{
    /**
     * Specification:
     * - Find item in quote
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $sku
     * @param string|null $groupKey
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function findItem(QuoteTransfer $quoteTransfer, $sku, $groupKey): array
    {
        return $this->getFactory()->getProductBundleClient()->findBundleItemsInQuote($quoteTransfer, $sku, $groupKey);
    }
}
