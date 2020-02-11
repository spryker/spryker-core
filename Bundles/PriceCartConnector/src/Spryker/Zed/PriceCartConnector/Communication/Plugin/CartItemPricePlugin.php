<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceCartConnector\Communication\Plugin;

use Generated\Shared\Transfer\CartChangeTransfer;
use Spryker\Zed\Cart\Dependency\ItemExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\PriceCartConnector\Business\PriceCartConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\PriceCartConnector\Communication\PriceCartConnectorCommunicationFactory getFactory()
 * @method \Spryker\Zed\PriceCartConnector\PriceCartConnectorConfig getConfig()
 */
class CartItemPricePlugin extends AbstractPlugin implements ItemExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandItems(CartChangeTransfer $cartChangeTransfer)
    {
        return $this->getFacade()->addPriceToItems($cartChangeTransfer);
    }
}
