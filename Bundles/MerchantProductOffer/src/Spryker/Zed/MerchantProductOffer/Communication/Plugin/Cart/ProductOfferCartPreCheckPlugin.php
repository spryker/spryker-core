<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOffer\Communication\Plugin\Cart;

use Generated\Shared\Transfer\CartChangeTransfer;
use Spryker\Zed\CartExtension\Dependency\Plugin\CartPreCheckPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\MerchantProductOffer\Business\MerchantProductOfferFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantProductOffer\MerchantProductOfferConfig getConfig()
 */
class ProductOfferCartPreCheckPlugin extends AbstractPlugin implements CartPreCheckPluginInterface
{
    /**
     * {@inheritDoc}
     * - Check if cart items product offer belongs to product.
     * - Returns pre-check transfer with error messages in case of error.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function check(CartChangeTransfer $cartChangeTransfer)
    {
        return $this->getFacade()->checkItemProductOffer($cartChangeTransfer);
    }
}
