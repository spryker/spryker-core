<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\Product;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductExtension\Dependency\Plugin\ProductConcreteCreatePluginInterface;

/**
 * @method \SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig getConfig()
 * @method \SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalBusinessFactory getBusinessFactory()
 * @method \SprykerFeature\Zed\SelfServicePortal\Communication\SelfServicePortalCommunicationFactory getFactory()
 */
class ShipmentTypeProductConcretePostCreatePlugin extends AbstractPlugin implements ProductConcreteCreatePluginInterface
{
    /**
     * {@inheritDoc}
     * - Expects `ProductConcreteTransfer.idProductConcrete` to be set.
     * - Expects `ProductConcreteTransfer.shipmentTypes` to be set.
     * - Creates product shipment type relations in persistence storage.
     * - Creates only new relations that don't exist.
     * - Triggers product concrete update event when relations are created.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function create(ProductConcreteTransfer $productConcreteTransfer): ProductConcreteTransfer
    {
        return $this->getBusinessFactory()
            ->createProductShipmentTypeSaver()
            ->saveProductShipmentTypes($productConcreteTransfer);
    }
}
