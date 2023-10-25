<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypeServicePointsRestApi\Communication\Plugin\CheckoutRestApi;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Spryker\Zed\CheckoutRestApiExtension\Dependency\Plugin\QuoteMapperPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ShipmentTypeServicePointsRestApi\Business\ShipmentTypeServicePointsRestApiFacadeInterface getFacade()
 * @method \Spryker\Zed\ShipmentTypeServicePointsRestApi\ShipmentTypeServicePointsRestApiConfig getConfig()
 */
class ShipmentTypeServicePointQuoteMapperPlugin extends AbstractPlugin implements QuoteMapperPluginInterface
{
    /**
     * {@inheritDoc}
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
    public function map(RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer, QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->getFacade()
            ->mapCustomerAddressDataToShippingAddresses($restCheckoutRequestAttributesTransfer, $quoteTransfer);
    }
}
