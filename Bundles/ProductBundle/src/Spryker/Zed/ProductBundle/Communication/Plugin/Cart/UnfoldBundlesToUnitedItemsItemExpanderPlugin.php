<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Communication\Plugin\Cart;

use Generated\Shared\Transfer\CartChangeTransfer;
use Spryker\Zed\CartExtension\Dependency\Plugin\ItemExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductBundle\Business\ProductBundleFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductBundle\Communication\ProductBundleCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductBundle\ProductBundleConfig getConfig()
 * @method \Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface getQueryContainer()
 */
class UnfoldBundlesToUnitedItemsItemExpanderPlugin extends AbstractPlugin implements ItemExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `CartChangeTransfer.quote.priceMode` to be set.
     * - Requires `id`, `sku`, `groupKey`, `quantity` and price (depending on mode) to be set for each element in `CartChangeTransfer.items`.
     * - Moves bundles from `CartChangeTransfer.items` to `CartChangeTransfer.quote.bundleItems` and adds bundled items instead.
     * - New bundle identifiers are assigned to bundles, which were moved to `CartChangeTransfer.quote.bundleItems`.
     * - Bundle price is distributed proportionally between all bundled items.
     * - Bundled items get into `CartChangeTransfer.items` united in one piece with a corresponding quantity,
     *   instead of being added individually with a quantity of 1. I.e. a bundle in `CartChangeTransfer.items`
     *   with a quantity of 3 will be replaced with groups of bundled items, each group also having a quantity of 3.
     * - Used instead of `ExpandBundleItemsPlugin`, when united bundled items approach is applied in cart.
     *
     * @api
     *
     * @see {@link \Spryker\Zed\ProductBundle\Communication\Plugin\Cart\ExpandBundleItemsPlugin}
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandItems(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        return $this->getFacade()->unfoldBundlesToUnitedItems($cartChangeTransfer);
    }
}
