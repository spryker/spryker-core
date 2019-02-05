<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductQuantityStorage\Plugin\QuickOrder;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ItemValidationResponseTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\QuickOrderExtension\Dependency\Plugin\ItemValidatorPluginInterface;

/**
 * @method \Spryker\Client\ProductQuantityStorage\ProductQuantityStorageClientInterface getClient()
 * @method \Spryker\Client\ProductQuantityStorage\ProductQuantityStorageFactory getFactory()
 */
class ProductQuantityItemValidatorPlugin extends AbstractPlugin implements ItemValidatorPluginInterface
{
    /**
     * {@inheritdoc}
     * - Checks if product concrete provided in ItemTransfer has product quantity restrictions or not.
     * - Adds recommendedValues with valid ItemTransfer->quantity inside into ItemValidationResponseTransfer and warning message
     *   when product has quantity restrictions.
     * - Returns empty ItemValidationResponseTransfer if product has not quantity restrictions.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemValidationResponseTransfer
     */
    public function validate(ItemTransfer $itemTransfer): ItemValidationResponseTransfer
    {
        return $this->getClient()->validateItemTransfer($itemTransfer);
    }
}
