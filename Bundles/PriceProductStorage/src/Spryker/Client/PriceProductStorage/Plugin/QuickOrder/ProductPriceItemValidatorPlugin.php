<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductStorage\Plugin\QuickOrder;

use Generated\Shared\Transfer\ItemValidationTransfer;
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
     * - Requires ItemTransfer inside ItemValidationTransfer.
     * - Returns not modified ItemValidationTransfer if ItemValidationTransfer.Item.id is missing.
     * - Gets ItemTransfer from the ItemValidationTransfer.
     * - Requires quantity and idProductAbstract in ItemTransfer if ItemTransfer.id is present.
     * - Creates PriceProductFilterTransfer and fill it with the quantity, id and idProductAbstract from the ItemTransfer.
     * - Tries to find product price using the PriceProductStorageClient::resolveCurrentProductPriceTransfer().
     * - Adds error message if price not found. Otherwise returns not modified ItemValidationTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemValidationTransfer $itemValidationTransfer
     *
     * @return \Generated\Shared\Transfer\ItemValidationTransfer
     */
    public function validate(ItemValidationTransfer $itemValidationTransfer): ItemValidationTransfer
    {
        return $this->getClient()->validateItemProductPrice($itemValidationTransfer);
    }
}
