<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductQuantity\Communication\Plugin\CartExtension;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Spryker\Zed\Cart\Dependency\TerminationAwareCartPreCheckPluginInterface;
use Spryker\Zed\CartExtension\Dependency\Plugin\CartRemovalPreCheckPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductQuantity\Business\ProductQuantityFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductQuantity\Communication\ProductQuantityCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductQuantity\ProductQuantityConfig getConfig()
 */
class ProductQuantityRestrictionCartRemovalPreCheckPlugin extends AbstractPlugin implements CartRemovalPreCheckPluginInterface, TerminationAwareCartPreCheckPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function check(CartChangeTransfer $cartChangeTransfer): CartPreCheckResponseTransfer
    {
        return $this->getFacade()
            ->validateItemRemoveProductQuantityRestrictions($cartChangeTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return bool
     */
    public function terminateOnFailure(): bool
    {
        return true;
    }
}
