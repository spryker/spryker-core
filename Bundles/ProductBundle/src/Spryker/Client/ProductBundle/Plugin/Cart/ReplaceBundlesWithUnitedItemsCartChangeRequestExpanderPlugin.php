<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductBundle\Plugin\Cart;

use Generated\Shared\Transfer\CartChangeTransfer;
use Spryker\Client\CartExtension\Dependency\Plugin\CartChangeRequestExpanderPluginInterface;
use Spryker\Client\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Client\ProductBundle\ProductBundleClient getClient()
 * @method \Spryker\Client\ProductBundle\ProductBundleFactory getFactory()
 */
class ReplaceBundlesWithUnitedItemsCartChangeRequestExpanderPlugin extends AbstractPlugin implements CartChangeRequestExpanderPluginInterface
{
    /**
     * Specification:
     * - Requires `CartChangeTransfer.quote` to be set.
     * - Requires `groupKey` and `quantity` to be set for each element in `CartChangeTransfer.items`.
     * - Replaces bundles in `CartChangeTransfer.items` with corresponding bundled items.
     * - Bundled items get into `CartChangeTransfer.items` united in one piece with a corresponding quantity,
     *   instead of being added individually with a quantity of 1. I.e. a bundle in `CartChangeTransfer.items`
     *   with a quantity of 3 will be replaced with groups of bundled items, each group also having a quantity of 3.
     * - Used instead of `RemoveBundleChangeRequestExpanderPlugin`, when united bundled items approach is applied in cart.
     *
     * @api
     *
     * @see {@link \Spryker\Client\ProductBundle\Plugin\Cart\RemoveBundleChangeRequestExpanderPlugin}
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expand(CartChangeTransfer $cartChangeTransfer, array $params = []): CartChangeTransfer
    {
        return $this->getClient()->replaceBundlesWithUnitedItems($cartChangeTransfer);
    }
}
