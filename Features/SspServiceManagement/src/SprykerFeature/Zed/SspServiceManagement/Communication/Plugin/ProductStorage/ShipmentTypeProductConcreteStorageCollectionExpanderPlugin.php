<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspServiceManagement\Communication\Plugin\ProductStorage;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductStorageExtension\Dependency\Plugin\ProductConcreteStorageCollectionExpanderPluginInterface;

/**
 * @method \SprykerFeature\Zed\SspServiceManagement\Business\SspServiceManagementBusinessFactory getBusinessFactory()
 * @method \SprykerFeature\Zed\SspServiceManagement\Business\SspServiceManagementFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SspServiceManagement\Communication\SspServiceManagementCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SspServiceManagement\SspServiceManagementConfig getConfig()
 */
class ShipmentTypeProductConcreteStorageCollectionExpanderPlugin extends AbstractPlugin implements ProductConcreteStorageCollectionExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands `ProductConcreteStorage` transfers with shipment type UUIDs.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\ProductConcreteStorageTransfer> $productConcreteStorageTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteStorageTransfer>
     */
    public function expand(array $productConcreteStorageTransfers): array
    {
        return $this->getBusinessFactory()
            ->createShipmentTypeProductConcreteStorageExpander()
            ->expandProductConcreteStorageTransfersWithShipmentTypes($productConcreteStorageTransfers);
    }
}
