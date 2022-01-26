<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductStorage\Communication\Plugin\ProductStorage;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductStorageExtension\Dependency\Plugin\ProductConcreteStorageCollectionExpanderPluginInterface;

/**
 * @method \Spryker\Zed\MerchantProductStorage\Communication\MerchantProductStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantProductStorage\Business\MerchantProductStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantProductStorage\MerchantProductStorageConfig getConfig()
 */
class MerchantProductConcreteStorageCollectionExpanderPlugin extends AbstractPlugin implements ProductConcreteStorageCollectionExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands ProductConcreteStorage transfers with merchant reference.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\ProductConcreteStorageTransfer> $productConcreteStorageTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteStorageTransfer>
     */
    public function expand(array $productConcreteStorageTransfers): array
    {
        return $this->getFacade()
            ->expandProductConcreteStorages($productConcreteStorageTransfers);
    }
}
