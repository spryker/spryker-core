<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductBundleCartsRestApi\Plugin\CartsRestApi;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Glue\CartsRestApiExtension\Dependency\Plugin\CartItemFilterPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\ProductBundleCartsRestApi\ProductBundleCartsRestApiFactory getFactory()
 */
class ProductBundleCartItemFilterPlugin extends AbstractPlugin implements CartItemFilterPluginInterface
{
    /**
     * {@inheritDoc}
     * - Filters out bundled items.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function filterCartItems(array $itemTransfers, QuoteTransfer $quoteTransfer): array
    {
        return $this->getFactory()->createBundleItemFilter()->filterBundleItems($itemTransfers, $quoteTransfer);
    }
}
