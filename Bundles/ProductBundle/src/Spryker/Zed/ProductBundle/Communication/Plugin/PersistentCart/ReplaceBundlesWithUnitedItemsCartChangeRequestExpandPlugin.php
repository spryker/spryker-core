<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Communication\Plugin\PersistentCart;

use Generated\Shared\Transfer\CartChangeTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PersistentCartExtension\Dependency\Plugin\CartChangeRequestExpandPluginInterface;

/**
 * @method \Spryker\Zed\ProductBundle\Business\ProductBundleFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductBundle\Communication\ProductBundleCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductBundle\ProductBundleConfig getConfig()
 * @method \Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface getQueryContainer()
 */
class ReplaceBundlesWithUnitedItemsCartChangeRequestExpandPlugin extends AbstractPlugin implements CartChangeRequestExpandPluginInterface
{
    /**
     * {@inheritDoc}
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
     * @see {@link \Spryker\Zed\ProductBundle\Communication\Plugin\PersistentCart\RemoveBundleChangeRequestExpanderPlugin}
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expand(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        return $this->getFacade()->replaceBundlesWithUnitedItems($cartChangeTransfer);
    }
}
