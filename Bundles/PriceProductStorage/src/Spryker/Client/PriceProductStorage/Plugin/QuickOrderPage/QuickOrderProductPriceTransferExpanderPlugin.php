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
 * @method \Spryker\Client\PriceProductStorage\PriceProductStorageFactory getFactory()
 * @method \Spryker\Client\PriceProductStorage\PriceProductStorageClientInterface getClient()
 */
class QuickOrderProductPriceTransferExpanderPlugin extends AbstractPlugin implements QuickOrderProductPriceTransferExpanderPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuickOrderProductPriceTransfer $quickOrderProductPriceTransfer
     *
     * @return void
     */
    public function expand(QuickOrderProductPriceTransfer $quickOrderProductPriceTransfer): void
    {
        if ($quickOrderProductPriceTransfer->getIdProductConcrete() === null) {
            return;
        }

        $priceProductConcreteTransfers = $this->getClient()->getPriceProductConcreteTransfers(
            $quickOrderProductPriceTransfer->getIdProductConcrete()
        );

        $currentProductPriceTransfer = $this->getFactory()
            ->getPriceProductClient()
            ->resolveProductPriceTransfer($priceProductConcreteTransfers);

        $quickOrderProductPriceTransfer->setCurrentProductPrice($currentProductPriceTransfer);
        $quickOrderProductPriceTransfer->setTotal($currentProductPriceTransfer->getPrice() * $quickOrderProductPriceTransfer->getQuantity());

        $quickOrderProductPriceTransfer->setCurrency(
            $this->getFactory()->getCurrencyClient()->getCurrent()
        );
    }
}
