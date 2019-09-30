<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductBundle;

use ArrayObject;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\ProductBundle\ProductBundleFactory getFactory()
 */
class ProductBundleClient extends AbstractClient implements ProductBundleClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $items
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $bundleItems
     *
     * @return array
     */
    public function getGroupedBundleItems(ArrayObject $items, ArrayObject $bundleItems)
    {
        return $this->getFactory()
            ->createProductBundleGrouper()
            ->getGroupedBundleItems($items, $bundleItems);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function getItemsWithBundlesItems(QuoteTransfer $quoteTransfer): array
    {
        return $this->getFactory()
            ->createProductBundleGrouper()
            ->getItemsWithBundlesItems($quoteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $sku
     * @param string|null $groupKey
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function findBundleItemsInQuote(QuoteTransfer $quoteTransfer, $sku, $groupKey): array
    {
        return $this->getFactory()
            ->createQuoteBundleItemsFinder()
            ->findBundledItems($quoteTransfer, $sku, $groupKey);
    }
}
