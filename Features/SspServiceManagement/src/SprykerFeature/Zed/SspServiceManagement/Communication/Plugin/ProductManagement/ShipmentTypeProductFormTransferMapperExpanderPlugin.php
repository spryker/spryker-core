<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspServiceManagement\Communication\Plugin\ProductManagement;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductFormTransferMapperExpanderPluginInterface;

/**
 * @method \SprykerFeature\Zed\SspServiceManagement\Business\SspServiceManagementFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SspServiceManagement\Communication\SspServiceManagementCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SspServiceManagement\SspServiceManagementConfig getConfig()
 */
class ShipmentTypeProductFormTransferMapperExpanderPlugin extends AbstractPlugin implements ProductFormTransferMapperExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Maps shipment type form data to `ProductConcreteTransfer`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcrete
     * @param array<string, mixed> $formData
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function map(ProductConcreteTransfer $productConcrete, array $formData): ProductConcreteTransfer
    {
        return $this->getFactory()
            ->createShipmentTypeProductFormMapper()
            ->mapShipmentTypeFormDataToProductConcrete($productConcrete, $formData);
    }
}
