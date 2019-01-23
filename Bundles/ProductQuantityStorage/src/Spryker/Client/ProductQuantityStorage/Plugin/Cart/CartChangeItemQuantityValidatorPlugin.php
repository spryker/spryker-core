<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductQuantityStorage\Plugin\Cart;

use Generated\Shared\Transfer\CartChangeItemValidationResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Client\CartExtension\Dependency\Plugin\CartChangeItemValidatorPluginInterface;
use Spryker\Client\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Client\ProductQuantityStorage\ProductQuantityStorageFactory getFactory()
 */
class CartChangeItemQuantityValidatorPlugin extends AbstractPlugin implements CartChangeItemValidatorPluginInterface
{
    /**
     * {@inheritdoc}
     * - Validate provided ItemTransfer with quantity validation.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeItemValidationResponseTransfer
     */
    public function validateItemTransfer(ItemTransfer $itemTransfer): CartChangeItemValidationResponseTransfer
    {
        return $this->getFactory()
            ->createQuantityCartChangeItemValidator()
            ->validateCartChangeItem($itemTransfer);
    }
}
