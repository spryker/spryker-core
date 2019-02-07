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
     * {@inheritdoc}
     * - Validates whether the product in provided ItemValidationTransfer is discontinued or not.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemValidationTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemValidationTransfer
     */
    public function validate(ItemValidationTransfer $itemTransfer): ItemValidationTransfer
    {
        return $this->getClient()->validateItemDiscontinued($itemTransfer);
    }
}
