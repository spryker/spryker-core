<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypeServicePointsRestApi\Business;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;

interface ShipmentTypeServicePointsRestApiFacadeInterface
{
    /**
     * Specification:
     * - Expects `RestCheckoutRequestAttributesTransfer.customer.customerReference` to be set.
     * - Expects `QuoteTransfer.items.shipment.method.idShipmentMethod` or `QuoteTransfer.shipment.method.idShipmentMethod` to be set.
     * - Expects `QuoteTransfer.items.shipment.shippingAddress` or `QuoteTransfer.shippingAddress` to be set.
     * - Expands shipping address with customer data if it's missing for applicable shipments.
     * - Uses {@link \Spryker\Zed\ShipmentTypeServicePointsRestApi\ShipmentTypeServicePointsRestApiConfig::getApplicableShipmentTypeKeysForShippingAddress()} to determine applicable shipments.
     * - Uses `RestCheckoutRequestAttributesTransfer.customer.customerReference` to get customer data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function mapCustomerAddressDataToShippingAddresses(
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer,
        QuoteTransfer $quoteTransfer
    ): QuoteTransfer;
}
