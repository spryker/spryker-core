<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOption\Communication\Plugin\Cart;

use Generated\Shared\Transfer\CartChangeTransfer;
use Spryker\Zed\CartExtension\Dependency\Plugin\CartPreCheckPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\MerchantProductOption\Business\MerchantProductOptionFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantProductOption\MerchantProductOptionConfig getConfig()
 * @method \Spryker\Zed\MerchantProductOption\Communication\MerchantProductOptionCommunicationFactory getFactory()
 */
class MerchantProductOptionCartPreCheckPlugin extends AbstractPlugin implements CartPreCheckPluginInterface
{
    /**
     * {@inheritDoc}
     * - Checks the approval status for merchant product options.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function check(CartChangeTransfer $cartChangeTransfer)
    {
        return $this->getFacade()->validateMerchantProductOptionsInCart($cartChangeTransfer);
    }
}
