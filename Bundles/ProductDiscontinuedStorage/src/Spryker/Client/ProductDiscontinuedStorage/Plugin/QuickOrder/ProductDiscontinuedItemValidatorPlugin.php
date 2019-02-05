<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductDiscontinuedStorage\Plugin\QuickOrder;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ItemValidationResponseTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\QuickOrderExtension\Dependency\Plugin\ItemValidatorPluginInterface;

/**
 * @method \Spryker\Client\ProductDiscontinuedStorage\ProductDiscontinuedStorageClientInterface getClient()
 * @method \Spryker\Client\ProductDiscontinuedStorage\ProductDiscontinuedStorageFactory getFactory()
 */
class ProductDiscontinuedItemValidatorPlugin extends AbstractPlugin implements ItemValidatorPluginInterface
{
    /**
     * {@inheritdoc}
     * - Validates whether the product in provided ItemTransfer is discontinued or not.
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
