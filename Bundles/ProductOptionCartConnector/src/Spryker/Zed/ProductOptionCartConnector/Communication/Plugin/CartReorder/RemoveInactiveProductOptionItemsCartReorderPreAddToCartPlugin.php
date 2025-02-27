<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOptionCartConnector\Communication\Plugin\CartReorder;

use Generated\Shared\Transfer\CartChangeTransfer;
use Spryker\Zed\CartReorderExtension\Dependency\Plugin\CartReorderPreAddToCartPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductOptionCartConnector\Business\ProductOptionCartConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductOptionCartConnector\Business\ProductOptionCartConnectorBusinessFactory getBusinessFactory()
 * @method \Spryker\Zed\ProductOptionCartConnector\Communication\ProductOptionCartConnectorCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductOptionCartConnector\ProductOptionCartConnectorConfig getConfig()
 */
class RemoveInactiveProductOptionItemsCartReorderPreAddToCartPlugin extends AbstractPlugin implements CartReorderPreAddToCartPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `ItemTransfer.productOption.idProductOptionValue` to be set for each item in `CartChangeTransfer.items`.
     * - Requires `ItemTransfer.sku` to be set for items with inactive product options in `CartChangeTransfer.items`.
     * - Filters out items with inactive product options from `CartChangeTransfer`.
     * - Adds a info message for each item's sku that has inactive product options.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function preAddToCart(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        return $this->getBusinessFactory()
            ->createInactiveProductOptionItemsFilter()
            ->filterOutInactiveProductOptionCartChangeItems($cartChangeTransfer);
    }
}
