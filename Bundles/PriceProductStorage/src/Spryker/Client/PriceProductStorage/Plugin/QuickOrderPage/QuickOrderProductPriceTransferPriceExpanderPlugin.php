<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductStorage\Plugin\QuickOrderPage;

use Generated\Shared\Transfer\QuickOrderProductPriceTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Zed\QuickOrderExtension\Dependency\Plugin\QuickOrderProductPriceTransferExpanderPluginInterface;

/**
 * @method \Spryker\Client\PriceProductStorage\PriceProductStorageClientInterface getClient()
 */
class QuickOrderProductPriceTransferPriceExpanderPlugin extends AbstractPlugin implements QuickOrderProductPriceTransferExpanderPluginInterface
{
    /**
     * {@inheritdoc}
     * - Provides total price calculated depending on quantity.
     * - Volume prices will be used if present.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuickOrderProductPriceTransfer $quickOrderProductPriceTransfer
     *
     * @return \Generated\Shared\Transfer\QuickOrderProductPriceTransfer
     */
    public function expandQuickOrderProductPriceTransfer(QuickOrderProductPriceTransfer $quickOrderProductPriceTransfer): QuickOrderProductPriceTransfer
    {
        return $this->getClient()->expandQuickOrderProductPriceTransferWithPrice($quickOrderProductPriceTransfer);
    }
}
