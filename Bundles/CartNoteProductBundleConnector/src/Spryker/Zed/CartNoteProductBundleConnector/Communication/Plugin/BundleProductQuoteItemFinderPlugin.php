<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartNoteProductBundleConnector\Communication\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CartNoteExtension\Dependency\Plugin\QuoteItemFinderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CartNoteProductBundleConnector\Communication\CartNoteProductBundleConnectorCommunicationFactory getFactory()
 */
class BundleProductQuoteItemFinderPlugin extends AbstractPlugin implements QuoteItemFinderPluginInterface
{
    /**
     * Specification:
     *  - Find item in quote.
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
        return $this->getFactory()->getProductBundleFacade()->findBundleItemsInQuote($quoteTransfer, $sku, $groupKey);
    }
}
