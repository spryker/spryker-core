<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentsRestApi\Plugin\GlueApplication;

use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Glue\ShipmentsRestApi\ShipmentsRestApiConfig;

/**
 * @Glue({
 *     "resourceAttributesClassName": "\\Generated\\Shared\\Transfer\\RestShipmentsAttributesTransfer"
 * })
 *
 * @method \Spryker\Glue\ShipmentsRestApi\ShipmentsRestApiFactory getFactory()
 */
class ShipmentsByCheckoutDataResourceRelationshipPlugin extends AbstractPlugin implements ResourceRelationshipPluginInterface
{
    /**
     * {@inheritDoc}
     * - Adds `shipments` resource as relationship if `RestCheckoutDataTransfer` is provided as payload.
     * - Uses `ShipmentService::groupItemsByShipment()` which exists in `Shipment` module from version `^7.0.0`.
     * - Is not applicable if `RestCheckoutDataTransfer` contains `shippingAddress` or `shipment` attributes.
     *
     * @api
     *
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return void
     */
    public function addResourceRelationships(array $resources, RestRequestInterface $restRequest): void
    {
        if (!$this->isSingleShipmentRequest($restRequest)) {
            return;
        }

        $this->getFactory()
            ->createShipmentByCheckoutDataExpander()
            ->addResourceRelationships($resources, $restRequest);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getRelationshipResourceType(): string
    {
        return ShipmentsRestApiConfig::RESOURCE_SHIPMENTS;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return bool
     */
    protected function isSingleShipmentRequest(RestRequestInterface $restRequest): bool
    {
        $restCheckoutRequestAttributesTransfer = $restRequest->getResource()->getAttributes();
        if (
            $restCheckoutRequestAttributesTransfer instanceof RestCheckoutRequestAttributesTransfer
            && !$restCheckoutRequestAttributesTransfer->getShippingAddress()
            && !$restCheckoutRequestAttributesTransfer->getShipment()
        ) {
            return true;
        }

        return false;
    }
}
