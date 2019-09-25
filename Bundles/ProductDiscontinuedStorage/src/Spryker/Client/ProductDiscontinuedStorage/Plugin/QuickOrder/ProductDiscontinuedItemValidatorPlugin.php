<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductDiscontinuedStorage\Plugin\QuickOrder;

use Generated\Shared\Transfer\ItemValidationTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\QuickOrderExtension\Dependency\Plugin\ItemValidatorPluginInterface;

/**
 * @method \Spryker\Client\ProductDiscontinuedStorage\ProductDiscontinuedStorageClientInterface getClient()
 * @method \Spryker\Client\ProductDiscontinuedStorage\ProductDiscontinuedStorageFactory getFactory()
 */
class ProductDiscontinuedItemValidatorPlugin extends AbstractPlugin implements ItemValidatorPluginInterface
{
    /**
     * {@inheritDoc}
     * - Returns not modified ItemValidationTransfer if ItemTransfer.id is missing.
     * - Requires sku inside ItemTransfer.
     * - Calls ProductDiscontinuedStorageClient::findProductDiscontinuedStorage() to know if product is discontinued or not (uses current locale).
     * - Adds error message if product is discontinued. Otherwise returns not modified ItemValidationTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemValidationTransfer $itemValidationTransfer
     *
     * @return \Generated\Shared\Transfer\ItemValidationTransfer
     */
    public function validate(ItemValidationTransfer $itemValidationTransfer): ItemValidationTransfer
    {
        return $this->getClient()->validateItemProductDiscontinued($itemValidationTransfer);
    }
}
