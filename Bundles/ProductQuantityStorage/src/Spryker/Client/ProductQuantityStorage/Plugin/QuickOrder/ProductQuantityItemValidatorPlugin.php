<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductQuantityStorage\Plugin\QuickOrder;

use Generated\Shared\Transfer\ItemValidationTransfer;
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
     * - Checks if product concrete provided in ItemValidationTransfer has product quantity restrictions or not.
     * - Adds recommendedValues with valid ItemValidationTransfer->quantity inside into ItemValidationTransfer and warning message
     *   when product has quantity restrictions.
     * - Returns empty ItemValidationTransfer if product has not quantity restrictions.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemValidationTransfer $itemValidationTransfer
     *
     * @return \Generated\Shared\Transfer\ItemValidationTransfer
     */
    public function validate(ItemValidationTransfer $itemValidationTransfer): ItemValidationTransfer
    {
        return $this->getClient()->validateItemQuantity($itemValidationTransfer);
    }
}
