<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfiguration\Communication\Plugin\Cart;

use Generated\Shared\Transfer\CartChangeTransfer;
use Spryker\Zed\CartExtension\Dependency\Plugin\ItemExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductConfiguration\ProductConfigurationConfig getConfig()
 * @method \Spryker\Zed\ProductConfiguration\Business\ProductConfigurationFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductConfiguration\Communication\ProductConfigurationCommunicationFactory getFactory()
 */
class ProductConfigurationGroupKeyItemExpanderPlugin extends AbstractPlugin implements ItemExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands items with product configuration instance property with a group key.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandItems(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        return $this->getFacade()->expandProductConfigurationItemsWithGroupKey($cartChangeTransfer);
    }
}
