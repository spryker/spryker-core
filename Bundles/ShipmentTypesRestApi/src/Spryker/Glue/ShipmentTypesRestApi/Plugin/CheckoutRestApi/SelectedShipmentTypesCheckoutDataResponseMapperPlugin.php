<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentTypesRestApi\Plugin\CheckoutRestApi;

use Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer;
use Generated\Shared\Transfer\RestCheckoutDataTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Spryker\Glue\CheckoutRestApiExtension\Dependency\Plugin\CheckoutDataResponseMapperPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @deprecated Added for BC reason only. Use {@link \Spryker\Glue\ShipmentTypesRestApi\Plugin\GlueApplication\ShipmentTypesByShipmentMethodsResourceRelationshipPlugin} instead.
 *
 * @method \Spryker\Glue\ShipmentTypesRestApi\ShipmentTypesRestApiFactory getFactory()
 */
class SelectedShipmentTypesCheckoutDataResponseMapperPlugin extends AbstractPlugin implements CheckoutDataResponseMapperPluginInterface
{
    /**
     * {@iheritDoc}
     * - Does nothing if `RestCheckoutRequestAttributesTransfer.shipment` is empty.
     * - Requires `RestCheckoutDataTransfer.quote` to be set.
     * - Expects `RestCheckoutDataTransfer.quote.items.shipmentType` to be set.
     * - Extracts shipment types from `Quote.items.shipmentType`.
     * - Maps extracted shipment types to `RestCheckoutDataResponseAttributesTransfer.selectedShipmentTypes`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RestCheckoutDataTransfer $restCheckoutDataTransfer
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     * @param \Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer $restCheckoutDataResponseAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer
     */
    public function mapRestCheckoutDataResponseTransferToRestCheckoutDataResponseAttributesTransfer(
        RestCheckoutDataTransfer $restCheckoutDataTransfer,
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer,
        RestCheckoutDataResponseAttributesTransfer $restCheckoutDataResponseAttributesTransfer
    ): RestCheckoutDataResponseAttributesTransfer {
        return $this->getFactory()
            ->createCheckoutDataResponseAttributesExpander()
            ->expandCheckoutDataResponseAttributesWithSelectedShipmentTypes(
                $restCheckoutDataTransfer,
                $restCheckoutRequestAttributesTransfer,
                $restCheckoutDataResponseAttributesTransfer,
            );
    }
}
