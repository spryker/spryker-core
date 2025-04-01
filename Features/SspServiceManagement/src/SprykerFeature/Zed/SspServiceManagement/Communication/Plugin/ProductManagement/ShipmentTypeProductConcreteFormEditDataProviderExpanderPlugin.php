<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspServiceManagement\Communication\Plugin\ProductManagement;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductConcreteFormEditDataProviderExpanderPluginInterface;

/**
 * @method \SprykerFeature\Zed\SspServiceManagement\Communication\SspServiceManagementCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SspServiceManagement\Business\SspServiceManagementFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SspServiceManagement\SspServiceManagementConfig getConfig()
 */
class ShipmentTypeProductConcreteFormEditDataProviderExpanderPlugin extends AbstractPlugin implements ProductConcreteFormEditDataProviderExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Maps shipment types from product concrete to form data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcrete
     * @param array<string, mixed> $formData
     *
     * @return void
     */
    public function expand(ProductConcreteTransfer $productConcrete, array &$formData): void
    {
        $formData = $this->getFactory()
            ->createShipmentTypeProductFormMapper()
            ->mapProductConcreteShipmentTypeToFormData($productConcrete, $formData);
    }
}
