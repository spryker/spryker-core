<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductQuantity\Communication\Plugin\Cart;

use Generated\Shared\Transfer\CartChangeTransfer;
use Spryker\Zed\CartExtension\Dependency\Plugin\CartChangeTransferNormalizerPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductQuantity\Business\ProductQuantityFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductQuantity\Communication\ProductQuantityCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductQuantity\ProductQuantityConfig getConfig()
 */
class CartChangeTransferQuantityNormalizerPlugin extends AbstractPlugin implements CartChangeTransferNormalizerPluginInterface
{
    protected const NORMALIZABLE_FIELD = 'quantity';

    /**
     * {@inheritdoc}
     * - Returns true if there is at least 1 ItemTransfer with a normalizable quantity field.
     * - Returns false otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return bool
     */
    public function isApplicable(CartChangeTransfer $cartChangeTransfer): bool
    {
        return $this->getFacade()
            ->hasCartChangeTransferNormalizableItems($cartChangeTransfer, [static::NORMALIZABLE_FIELD]);
    }

    /**
     * {@inheritdoc}
     * - Adjusts cart item quantity according to product quantity restrictions.
     * - Adds notification message about adjustment.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function normalizeCartChangeTransfer(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        return $this->getFacade()
            ->normalizeCartChangeTransferItems($cartChangeTransfer);
    }
}
