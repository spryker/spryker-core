<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductBundleCartsRestApi\Processor\Filter;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Glue\ProductBundleCartsRestApi\Dependency\Client\ProductBundleCartsRestApiToProductBundleClientInterface;

class BundleItemFilter implements BundleItemFilterInterface
{
    /**
     * @var \Spryker\Glue\ProductBundleCartsRestApi\Dependency\Client\ProductBundleCartsRestApiToProductBundleClientInterface
     */
    protected $productBundleClient;

    /**
     * @param \Spryker\Glue\ProductBundleCartsRestApi\Dependency\Client\ProductBundleCartsRestApiToProductBundleClientInterface $productBundleClient
     */
    public function __construct(ProductBundleCartsRestApiToProductBundleClientInterface $productBundleClient)
    {
        $this->productBundleClient = $productBundleClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function filterBundleItems(array $itemTransfers, QuoteTransfer $quoteTransfer): array
    {
        $filteredItemTransfers = [];
        $groupedCartItems = $this->productBundleClient->getGroupedBundleItems(new ArrayObject($itemTransfers), $quoteTransfer->getBundleItems());

        foreach ($groupedCartItems as $cartItem) {
            if (!$cartItem instanceof ItemTransfer) {
                continue;
            }

            $filteredItemTransfers[] = $cartItem;
        }

        return $filteredItemTransfers;
    }
}
