<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Locale\Communication\Plugin\Store;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\StoreExtension\Dependency\Plugin\StoreCollectionExpanderPluginInterface;

/**
 * @method \Spryker\Zed\Locale\Business\LocaleFacadeInterface getFacade()
 * @method \Spryker\Zed\Locale\LocaleConfig getConfig()
 * @method \Spryker\Zed\Locale\Communication\LocaleCommunicationFactory getFactory()
 */
class LocaleStoreCollectionExpanderPlugin extends AbstractPlugin implements StoreCollectionExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands store transfers with locale codes.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\StoreTransfer> $storeTransfers
     *
     * @return array<\Generated\Shared\Transfer\StoreTransfer>
     */
    public function expand(array $storeTransfers): array
    {
        return $this->getFacade()->expandStoreTransfersWithLocales($storeTransfers);
    }
}
