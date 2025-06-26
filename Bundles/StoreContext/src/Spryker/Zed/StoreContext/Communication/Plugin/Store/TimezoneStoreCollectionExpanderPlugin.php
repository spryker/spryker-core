<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreContext\Communication\Plugin\Store;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\StoreExtension\Dependency\Plugin\StoreCollectionExpanderPluginInterface;

/**
 * Should be wired after `ContextStoreCollectionExpanderPlugin` to ensure that timezone information is available.
 *
 * @method \Spryker\Zed\StoreContext\Communication\StoreContextCommunicationFactory getFactory()
 * @method \Spryker\Zed\StoreContext\Business\StoreContextFacadeInterface getFacade()
 * @method \Spryker\Zed\StoreContext\Business\StoreContextBusinessFactory getBusinessFactory()
 * @method \Spryker\Zed\StoreContext\StoreContextConfig getConfig()
 */
class TimezoneStoreCollectionExpanderPlugin extends AbstractPlugin implements StoreCollectionExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands store transfers with timezone information taken from the store context.
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\StoreTransfer> $storeTransfers
     *
     * @return list<\Generated\Shared\Transfer\StoreTransfer>
     */
    public function expand(array $storeTransfers): array
    {
        return $this->getBusinessFactory()->createStoreExpander()->expandStoreTransfersWithTimezone($storeTransfers);
    }
}
