<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Currency\Communication\Plugin\Store;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\StoreExtension\Dependency\Plugin\StoreCollectionExpanderPluginInterface;

/**
 * @method \Spryker\Zed\Currency\Business\CurrencyFacadeInterface getFacade()
 * @method \Spryker\Zed\Currency\CurrencyConfig getConfig()
 * @method \Spryker\Zed\Currency\Communication\CurrencyCommunicationFactory getFactory()
 */
class CurrencyStoreCollectionExpanderPlugin extends AbstractPlugin implements StoreCollectionExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands store transfers with currency codes.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\StoreTransfer> $storeTransfers
     *
     * @return array<\Generated\Shared\Transfer\StoreTransfer>
     */
    public function expand(array $storeTransfers): array
    {
        return $this->getFacade()->expandStoreTransfersWithCurrencies($storeTransfers);
    }
}
