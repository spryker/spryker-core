<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinued\Communication\Plugin\Cart;

use Generated\Shared\Transfer\CartChangeTransfer;
use Spryker\Zed\Cart\Dependency\CartPreCheckPluginInterface;
use Spryker\Zed\Cart\Dependency\TerminationAwareCartPreCheckPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductDiscontinued\Business\ProductDiscontinuedFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductDiscontinued\Communication\ProductDiscontinuedCommunicationFactory getFactory()
 */
class ProductDiscontinuedCartPreCheckPlugin extends AbstractPlugin implements CartPreCheckPluginInterface, TerminationAwareCartPreCheckPluginInterface
{
    /**
     *
     * Specification:
     * - This plugin is executed before cart add operation is executed,
     *   for example could be used to check if item quantity is available for selected item
     *   Should return CartPreCheckResponseTransfer where error messages set and flag that check failed.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function check(CartChangeTransfer $cartChangeTransfer)
    {
        return $this->getFacade()
            ->checkItemProductsIsNotDiscontinued($cartChangeTransfer);
    }

    /**
     * Specification:
     * - Describes should the cart pre-check process be stopped in case of failure
     *
     * @api
     *
     * @return bool
     */
    public function terminateOnFailure()
    {
        return true;
    }
}
