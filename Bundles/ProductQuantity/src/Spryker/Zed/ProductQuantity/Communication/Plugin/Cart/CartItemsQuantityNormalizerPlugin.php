<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductQuantity\Communication\Plugin\Cart;

use Generated\Shared\Transfer\CartChangeTransfer;
use Spryker\Zed\CartExtension\Dependency\Plugin\CartItemsNormalizerPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductQuantity\Business\ProductQuantityFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductQuantity\Communication\ProductQuantityCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductQuantity\ProductQuantityConfig getConfig()
 */
class CartItemsQuantityNormalizerPlugin extends AbstractPlugin implements CartItemsNormalizerPluginInterface
{
    protected const NORMALIZABLE_FIELD = 'quantity';

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return bool
     */
    public function isApplicable($cartChangeTransfer): bool
    {
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            $normalizableFields = $itemTransfer->getNormalizableFields();

            if (count($normalizableFields) > 0 && in_array(static::NORMALIZABLE_FIELD, $normalizableFields)) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     * - Adjusts cart item quantity according to product quantity restrictions.
     * - Adds notification message about adjusment.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function normalizeItems($cartChangeTransfer): CartChangeTransfer
    {
        return $this->getFacade()
            ->normalizeItemsWithProductQuantityRestrictions($cartChangeTransfer);
    }
}
