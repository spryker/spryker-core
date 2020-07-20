<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductBundle\Plugin\Cart;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Cart\Dependency\Plugin\ItemCountPluginInterface;
use Spryker\Client\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Client\ProductBundle\ProductBundleFactory getFactory()
 */
class ProductBundleItemCountQuantityPlugin extends AbstractPlugin implements ItemCountPluginInterface
{
    /**
     * Specification:
     *  - Returns combined quantity of all items in cart.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int
     */
    public function getItemCount(QuoteTransfer $quoteTransfer)
    {
        return $this->getFactory()
            ->createItemQuantityCounter()
            ->getItemCount($quoteTransfer);
    }
}
