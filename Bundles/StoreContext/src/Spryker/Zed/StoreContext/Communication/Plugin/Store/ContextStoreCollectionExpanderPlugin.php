<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreContext\Communication\Plugin\Store;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\StoreExtension\Dependency\Plugin\StoreCollectionExpanderPluginInterface;

/**
 * @method \Spryker\Zed\StoreContext\Communication\StoreContextCommunicationFactory getFactory()
 * @method \Spryker\Zed\StoreContext\Business\StoreContextFacadeInterface getFacade()
 * @method \Spryker\Zed\StoreContext\StoreContextConfig getConfig()
 */
class ContextStoreCollectionExpanderPlugin extends AbstractPlugin implements StoreCollectionExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands store transfers with store context collections.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\StoreTransfer> $storeTransfers
     *
     * @return array<\Generated\Shared\Transfer\StoreTransfer>
     */
    public function expand(array $storeTransfers): array
    {
        $storeCollectionTransfer = $this->getFactory()
            ->createStoreContextMapper()
            ->mapStoreTransfersToStoreCollectionTransfer($storeTransfers);

        return $this->getFactory()
            ->createStoreContextMapper()
            ->mapStoreCollectionTransferToStoreTransfers(
                $this->getFacade()->expandStoreCollection($storeCollectionTransfer),
            );
    }
}
