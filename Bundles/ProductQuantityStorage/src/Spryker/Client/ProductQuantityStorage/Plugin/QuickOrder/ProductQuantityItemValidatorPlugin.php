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
     * {@inheritDoc}
     * - Requires ItemTransfer inside ItemValidationTransfer.
     * - Returns not modified ItemValidationTransfer if ItemTransfer.id is missing.
     * - Calls ProductQuantityStorageClient::findProductQuantityStorage() to find product quantity restrictions.
     * - Returns not modified ItemValidationTransfer if product quantity restrictions was not found.
     * - Requires quantity inside ItemTransfer and checks it with the product quantity restrictions.
     * - Returns ItemValidationTransfer with messages and suggestedValues.quantity in case if ItemTransfer.quantity falls in restrictions.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemValidationTransfer $itemValidationTransfer
     *
     * @return \Generated\Shared\Transfer\ItemValidationTransfer
     */
    public function validate(ItemValidationTransfer $itemValidationTransfer): ItemValidationTransfer
    {
        return $this->getClient()->validateItemProductQuantity($itemValidationTransfer);
    }
}
