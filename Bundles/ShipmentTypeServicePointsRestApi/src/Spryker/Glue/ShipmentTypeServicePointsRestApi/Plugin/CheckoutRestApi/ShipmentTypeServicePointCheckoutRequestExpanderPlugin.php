<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentTypeServicePointsRestApi\Plugin\CheckoutRestApi;

use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Spryker\Glue\CheckoutRestApiExtension\Dependency\Plugin\CheckoutRequestExpanderPluginInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\ShipmentTypeServicePointsRestApi\ShipmentTypeServicePointsRestApiFactory getFactory()
 */
class ShipmentTypeServicePointCheckoutRequestExpanderPlugin extends AbstractPlugin implements CheckoutRequestExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Checks if any of provided shipment methods are related to applicable shipment types.
     * - Maps provided service point address to shipping address.
     * - Uses `RestCheckoutRequestAttributesTransfer.customer` data to complete shipping address information.
     *
     * @api
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer
     */
    public function expand(
        RestRequestInterface $restRequest,
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
    ): RestCheckoutRequestAttributesTransfer {
        return $this->getFactory()
            ->createServicePointAddressExpanderStrategyResolver()
            ->resolveAddressExpander($restCheckoutRequestAttributesTransfer)
            ->expandRestCheckoutRequestAttributesTransfer($restCheckoutRequestAttributesTransfer);
    }
}
