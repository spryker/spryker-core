<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundleCartsRestApi\Business;

use Generated\Shared\Transfer\CartItemRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductBundleCartsRestApi\Business\ProductBundleCartsRestApiBusinessFactory getFactory()
 */
class ProductBundleCartsRestApiFacade extends AbstractFacade implements ProductBundleCartsRestApiFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartItemRequestTransfer $cartItemRequestTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function validateBundleItem(CartItemRequestTransfer $cartItemRequestTransfer, QuoteTransfer $quoteTransfer): bool
    {
        return $this->getFactory()
            ->createBundleItemChecker()
            ->validateBundleItem($cartItemRequestTransfer, $quoteTransfer);
    }
}
