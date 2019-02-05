<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductStorage\Plugin;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ItemValidationResponseTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\QuickOrderExtension\Dependency\Plugin\ItemValidatorPluginInterface;

/**
 * @method \Spryker\Client\PriceProductStorage\PriceProductStorageClientInterface getClient()
 * @method \Spryker\Client\PriceProductStorage\PriceProductStorageFactory getFactory()
 */
class ProductPriceItemValidatorPlugin extends AbstractPlugin implements ItemValidatorPluginInterface
{
    /**
     * {@inheritdoc}
     * - Checks if product concrete provided in ItemTransfer has price or not.
     * - If price is not found adds error message to ItemValidationResponseTransfer.
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
